<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once __DIR__ . '/shortcode.lib.php';

/**
 * OKOME 主题功能类
 * 提供主题相关的功能方法
 */
class OKOME
{
    /**
     * 处理评论中的表情符号
     * 将评论中的表情触发词替换为对应的图片
     * 
     * @param string $commentStr 评论内容
     * @return string 处理后的评论内容
     */
    public static function processCommentEmoji($commentStr)
    {
        if (empty($commentStr)) {
            return $commentStr;
        }

        $EmojiJson = rtrim(get_site_url(false), '/') . '/okome-hikari-api/owo';
        $json = @file_get_contents($EmojiJson);
        if ($json === false) {
            return $commentStr;
        }

        $data = json_decode($json, true);
        if (!is_array($data)) {
            return $commentStr;
        }

        foreach ($data as $category) {
            if (!is_array($category)) {
                continue;
            }

            if (isset($category['type']) && $category['type'] === 'image' && isset($category['container']) && is_array($category['container'])) {
                foreach ($category['container'] as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $trigger = isset($item['input']) ? (string)$item['input'] : '';
                    $icon = isset($item['icon']) ? (string)$item['icon'] : '';
                    if ($trigger === '' || $icon === '') {
                        continue;
                    }

                    $alt = isset($item['text']) ? (string)$item['text'] : '';
                    $img = '<img src="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '" class="owo-emoji">';
                    $commentStr = str_replace($trigger, $img, $commentStr);
                }
            }
        }

        return $commentStr;
    }
}

// Shortcode 函数
function shortcode_notice($atts, $content = '')
{
    return "<div role='alert' class='alert alert-info '>
    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='h-6 w-6 shrink-0 stroke-current'>
      <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
    </svg>
    <span>" . $content . "</span>
  </div>";
}

function shortcode_alert($atts, $content = '')
{
    return "<div role='alert' class='alert  '>
    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='h-6 w-6 shrink-0 stroke-current'>
      <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
    </svg>
    <span>" . $content . "</span>
  </div>";
}

function shortcode_error($atts, $content = '')
{
    return "<div role='alert' class='alert alert-error'>
    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='h-6 w-6 shrink-0 stroke-current'>
      <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
    </svg>
    <span>" . $content . "</span>
  </div>";
}

function shortcode_bili($atts, $content = '')
{
    if (preg_match('/[a-zA-Z]/', $content)) {
        return '<iframe class="bilibili w-full min-h-60 md:min-h-120 rounded-box" src="//player.bilibili.com/player.html?bvid=' . $content . '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>';
    } else {
        return '<iframe class="bilibili w-full min-h-60 md:min-h-120 rounded-box" src="//player.bilibili.com/player.html?aid=' . $content . '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>';
    }
}

add_shortcode('bili', 'shortcode_bili');
add_shortcode('notice', 'shortcode_notice');
add_shortcode('alert', 'shortcode_alert');
add_shortcode('error', 'shortcode_error');

// Aplayer本地音乐播放器
function shortcode_aplayer($atts, $content = '')
{
    $args = shortcode_atts(array(
        'id' => '',
        'artist' => '',
        'url' => '',
        'pic' => ''
    ), $atts);
    return "<div id='aplayer-" . $args['id'] . "'></div>
    <script data-swup-reload-script>
    (function(){
      var el = document.getElementById('aplayer-" . $args["id"] . "');
      if(!el) return;
      var aplayer" . $args["id"] . " = new APlayer({
        container: el,
        audio: [{
            name: '" . $content . "',
            artist: '" . $args['artist'] . "',
            url: '" . $args['url'] . "',
            cover: '" . $args['pic'] . "'
        }]
      });
    })();
    </script>
    ";
}

add_shortcode('aplayer', 'shortcode_aplayer');

// Aplayer MetingJS
// 单曲
function shortcode_aplayer_meting_single($atts, $content = '')
{
    $args = shortcode_atts(array(
        'id' => ''
    ), $atts);
    $uid = $args['id'] ?: uniqid();
    $baseApi = Get::Options('MetingApiUrl', false);
    $api = $baseApi ? ($baseApi . '?type=song&id=' . urlencode($content)) : '';
    $audio = [];
    if ($api) {
        $resp = @file_get_contents($api);
        if ($resp === false && function_exists('curl_init')) {
            $ch = curl_init($api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'TTDF-APlayer');
            $resp = curl_exec($ch);
            curl_close($ch);
        }
        if ($resp) {
            $decoded = json_decode($resp, true);
            if (is_array($decoded) && !empty($decoded)) {
                $first = $decoded[0];
                $audio = [
                    'name' => (string)($first['name'] ?? ''),
                    'artist' => (string)($first['artist'] ?? ''),
                    'url' => (string)($first['url'] ?? ''),
                    'cover' => (string)($first['pic'] ?? ''),
                    'lrc' => (string)($first['lrc'] ?? '')
                ];
            }
        }
    }
    $audioJson = json_encode($audio ? [$audio] : [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return "<div id='aplayer-meting-" . $uid . "'></div>
    <script data-swup-reload-script>
    (function(){
      var el = document.getElementById('aplayer-meting-" . $uid . "');
      if(!el) return;
      var aplayer" . $uid . " = new APlayer({
        container: el,
        lrcType: 3,
        audio: " . $audioJson . "
      });
    })();
    </script>
    ";
}

// 列表
function shortcode_aplayer_meting_list($atts, $content = '')
{
    $args = shortcode_atts(array(
        'id' => '',
    ), $atts);
    $uid = $args['id'] ?: uniqid();
    $baseApi = Get::Options('MetingApiUrl', false);
    $api = $baseApi ? ($baseApi . '?type=playlist&id=' . urlencode($content)) : '';
    $list = [];
    if ($api) {
        $resp = @file_get_contents($api);
        if ($resp === false && function_exists('curl_init')) {
            $ch = curl_init($api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'TTDF-APlayer');
            $resp = curl_exec($ch);
            curl_close($ch);
        }
        if ($resp) {
            $decoded = json_decode($resp, true);
            if (is_array($decoded) && !empty($decoded)) {
                $i = 0;
                foreach ($decoded as $item) {
                    $list[] = [
                        'name' => (string)($item['name'] ?? ''),
                        'artist' => (string)($item['artist'] ?? ''),
                        'url' => (string)($item['url'] ?? ''),
                        'cover' => (string)($item['pic'] ?? ''),
                        'lrc' => (string)($item['lrc'] ?? ''),
                    ];
                    $i++;
                }
            }
        }
    }
    $listJson = json_encode($list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return "<div id='aplayer-meting-list-" . $uid . "'></div>
    <script data-swup-reload-script>
    (function(){
      var el = document.getElementById('aplayer-meting-list-" . $uid . "');
      if(!el) return;
      var list = " . $listJson . ";
      if(!list.length) return;
      var aplayer" . $uid . " = new APlayer({
        container: el,
        lrcType: 3,
        audio: list,
      });
    })();
    </script>
    ";
}

add_shortcode('meting_single', 'shortcode_aplayer_meting_single');
add_shortcode('meting_list', 'shortcode_aplayer_meting_list');

// 自定义文章编辑器按钮
Typecho_Plugin::factory('admin/write-post.php')->bottom = array('Editor', 'edit');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('Editor', 'edit');
class Editor
{
    public static function edit()
    {
?>
    <script src="<?php get_assets('editor.js') ?>"></script>
<?php
    }
}

