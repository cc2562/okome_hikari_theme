<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

const OKOME_BANGUMI_CACHE_TTL = 3600;

function okome_bangumi_json($payload, int $status = 200)
{
    http_response_code($status);
    if (class_exists('Typecho_Response')) {
        Typecho_Response::getInstance()->setStatus($status);
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function okome_bangumi_option(string $name, string $default = ''): string
{
    $value = Get::Options($name);
    if ($value === null) {
        return $default;
    }
    return trim((string) $value);
}

function okome_bangumi_cache_file(string $username): string
{
    return __DIR__ . '/../../cache/bangumi-' . md5($username) . '.json';
}

function okome_bangumi_read_cache(string $username): ?array
{
    $file = okome_bangumi_cache_file($username);
    if (!is_file($file)) {
        return null;
    }

    $raw = file_get_contents($file);
    if ($raw === false) {
        return null;
    }

    $payload = json_decode($raw, true);
    if (!is_array($payload) || !isset($payload['cachedAt'])) {
        return null;
    }

    return $payload;
}

function okome_bangumi_cache_is_fresh(array $payload): bool
{
    return isset($payload['cachedAt']) && (time() - (int) $payload['cachedAt']) < OKOME_BANGUMI_CACHE_TTL;
}

function okome_bangumi_write_cache(string $username, array $payload): void
{
    $file = okome_bangumi_cache_file($username);
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($file, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function okome_bangumi_request(string $url, string $ua): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        return [
            'ok' => $errno === 0 && $status >= 200 && $status < 300 && $body !== false,
            'status' => $status ?: 500,
            'body' => $body === false ? '' : (string) $body,
            'error' => $errno ? $error : '',
        ];
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 12,
            'header' => "Accept: application/json\r\nUser-Agent: {$ua}\r\n",
            'ignore_errors' => true,
        ],
    ]);
    $body = @file_get_contents($url, false, $context);
    $status = 500;

    if (isset($http_response_header) && is_array($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (preg_match('/^HTTP\/\S+\s+(\d+)/', $header, $matches)) {
                $status = (int) $matches[1];
                break;
            }
        }
    }

    return [
        'ok' => $body !== false && $status >= 200 && $status < 300,
        'status' => $status,
        'body' => $body === false ? '' : (string) $body,
        'error' => $body === false ? '请求 Bangumi API 失败' : '',
    ];
}

function okome_bangumi_normalize_item(array $item): array
{
    $subject = isset($item['subject']) && is_array($item['subject']) ? $item['subject'] : [];
    $images = isset($subject['images']) && is_array($subject['images']) ? $subject['images'] : [];

    return [
        'updated_at' => $item['updated_at'] ?? '',
        'comment' => $item['comment'] ?? '',
        'tags' => isset($item['tags']) && is_array($item['tags']) ? array_values($item['tags']) : [],
        'ep_status' => $item['ep_status'] ?? 0,
        'rate' => $item['rate'] ?? 0,
        'subject' => [
            'id' => $subject['id'] ?? 0,
            'name' => $subject['name'] ?? '',
            'name_cn' => $subject['name_cn'] ?? '',
            'date' => $subject['date'] ?? '',
            'eps' => $subject['eps'] ?? 0,
            'rank' => $subject['rank'] ?? 0,
            'images' => [
                'large' => $images['large'] ?? '',
                'common' => $images['common'] ?? '',
                'medium' => $images['medium'] ?? '',
                'small' => $images['small'] ?? '',
                'grid' => $images['grid'] ?? '',
            ],
        ],
    ];
}

function okome_bangumi_fetch_collections(string $username, string $ua, int $type): array
{
    $limit = 50;
    $offset = 0;
    $items = [];
    $total = 0;

    do {
        $url = 'https://api.bgm.tv/v0/users/' . rawurlencode($username) . '/collections?subject_type=2&type=' . $type . '&limit=' . $limit . '&offset=' . $offset;
        $response = okome_bangumi_request($url, $ua);

        if (!$response['ok']) {
            okome_bangumi_json([
                'error' => 'bangumi_request_failed',
                'message' => $response['error'] ?: '请求 Bangumi API 失败。',
                'status' => $response['status'],
            ], 502);
        }

        $json = json_decode($response['body'], true);
        if (!is_array($json)) {
            okome_bangumi_json([
                'error' => 'invalid_bangumi_response',
                'message' => 'Bangumi API 返回了无效 JSON。',
            ], 502);
        }

        $pageData = isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
        $total = isset($json['total']) ? (int) $json['total'] : count($pageData);

        foreach ($pageData as $item) {
            if (is_array($item)) {
                $items[] = okome_bangumi_normalize_item($item);
            }
        }

        $offset += $limit;
    } while (count($pageData) === $limit && $offset < $total && $offset <= 500);

    return [
        'items' => $items,
        'total' => $total ?: count($items),
    ];
}

$username = okome_bangumi_option('bangumiUserId');
$ua = okome_bangumi_option('bangumiUserAgent', 'okome-hikari/typecho-theme (https://github.com/YuiNijika/okome_hikari)');

if ($username === '') {
    okome_bangumi_json([
        'error' => 'missing_username',
        'message' => '请先在主题设置中填写 Bangumi 用户名/UID。',
    ], 400);
}

if ($ua === '') {
    okome_bangumi_json([
        'error' => 'missing_user_agent',
        'message' => '请先在主题设置中填写 Bangumi User Agent。',
    ], 400);
}

$forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === '1';
$cache = okome_bangumi_read_cache($username);

if (!$forceRefresh && $cache !== null && okome_bangumi_cache_is_fresh($cache)) {
    $cache['fromCache'] = true;
    $cache['cacheExpiresAt'] = gmdate('c', (int) $cache['cachedAt'] + OKOME_BANGUMI_CACHE_TTL);
    okome_bangumi_json($cache);
}

$watching = okome_bangumi_fetch_collections($username, $ua, 3);
$watched = okome_bangumi_fetch_collections($username, $ua, 2);

$payload = [
    'items' => $watching['items'],
    'total' => $watching['total'],
    'watching' => $watching,
    'watched' => $watched,
    'fetchedAt' => gmdate('c'),
    'cachedAt' => time(),
    'fromCache' => false,
    'cacheExpiresAt' => gmdate('c', time() + OKOME_BANGUMI_CACHE_TTL),
];

okome_bangumi_write_cache($username, $payload);
okome_bangumi_json($payload);
