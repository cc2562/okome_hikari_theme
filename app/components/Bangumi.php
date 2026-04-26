<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$bangumiTitle = trim((string) Get::Options('bangumiListTitle'));
if ($bangumiTitle === '') {
    $bangumiTitle = '正在追番';
}

$bangumiUserId = trim((string) Get::Options('bangumiUserId'));
$siteUrl = rtrim((string) get_site_url(false), '/');
$bangumiRoute = defined('__TTDF_RESTAPI_ROUTE__') ? trim((string) __TTDF_RESTAPI_ROUTE__, '/') : 'hikari';
$bangumiApiUrl = $siteUrl . '/' . $bangumiRoute . '/bangumi';
?>
<main>
    <div class="mx-auto w-full max-w-full sm:max-w-5xl sm:px-4 flex flex-col gap-6">
        <div class="card bg-base-100 p-4 md:p-8 shadow-sm">
            <article class="prose max-w-none">
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($bangumiTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                <?php if ($bangumiUserId === ''): ?>
                    <div class="text-sm text-gray-500 mb-6">
                        请先在主题设置中填写 Bangumi 用户名/UID
                    </div>
                <?php endif; ?>

                <section
                    id="bangumi-watch-list"
                    class="bangumi-watch-list"
                    data-api="<?php echo htmlspecialchars($bangumiApiUrl, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="bangumi-status bangumi-status-loading">
                        <span class="loading loading-spinner text-primary"></span>
                        <span>正在读取追番列表...</span>
                    </div>
                </section>
            </article>
        </div>
    </div>
    <div class="mt-4"></div>

    <style>
        .bangumi-watch-list {
            margin-top: 1.5rem;
        }

        .bangumi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        .bangumi-section + .bangumi-section {
            margin-top: 2rem;
        }

        .bangumi-section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.875rem;
        }

        .bangumi-section-title {
            margin: 0;
            font-size: 1.25rem;
            line-height: 1.3;
            font-weight: 800;
            color: var(--color-base-content);
        }

        .bangumi-section-count {
            margin: 0;
            font-size: 0.875rem;
            color: color-mix(in oklab, var(--color-base-content) 60%, transparent);
        }

        .bangumi-card {
            position: relative;
            display: grid;
            grid-template-columns: 96px minmax(0, 1fr);
            gap: 1rem;
            min-height: 158px;
            padding: 0.85rem;
            border-radius: var(--radius-box);
            overflow: hidden;
            background:
                linear-gradient(135deg, color-mix(in oklab, var(--color-primary) 18%, transparent), transparent 42%),
                var(--color-base-200);
            color: var(--color-base-content);
            text-decoration: none !important;
            box-shadow: inset 0 0 0 1px color-mix(in oklab, var(--color-base-content) 8%, transparent);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .bangumi-card::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(circle at top right, color-mix(in oklab, var(--color-accent) 14%, transparent), transparent 38%);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .bangumi-card:hover {
            transform: translateY(-2px);
            box-shadow:
                inset 0 0 0 1px color-mix(in oklab, var(--color-primary) 35%, transparent),
                0 14px 30px color-mix(in oklab, var(--color-neutral) 12%, transparent);
        }

        .bangumi-card:hover::before {
            opacity: 1;
        }

        .bangumi-cover {
            position: relative;
            z-index: 1;
            width: 96px;
            aspect-ratio: 3 / 4;
            border-radius: calc(var(--radius-box) * 0.75);
            overflow: hidden;
            background: var(--color-base-300);
            box-shadow: 0 10px 18px color-mix(in oklab, var(--color-neutral) 18%, transparent);
        }

        .bangumi-cover img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            margin: 0;
            border-radius: 0;
        }

        .bangumi-cover-placeholder {
            display: grid;
            place-items: center;
            width: 100%;
            height: 100%;
            color: color-mix(in oklab, var(--color-base-content) 45%, transparent);
            font-weight: 700;
            background:
                linear-gradient(135deg, color-mix(in oklab, var(--color-primary) 28%, transparent), transparent),
                var(--color-base-300);
        }

        .bangumi-info {
            position: relative;
            z-index: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.42rem;
        }

        .bangumi-title {
            margin: 0;
            display: -webkit-box;
            overflow: hidden;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            font-size: 1.02rem;
            line-height: 1.35;
            font-weight: 700;
            color: var(--color-base-content);
        }

        .bangumi-subtitle,
        .bangumi-meta,
        .bangumi-comment {
            margin: 0;
            font-size: 0.82rem;
            line-height: 1.5;
            color: color-mix(in oklab, var(--color-base-content) 65%, transparent);
        }

        .bangumi-subtitle {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .bangumi-comment {
            display: -webkit-box;
            overflow: hidden;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .bangumi-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }

        .bangumi-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            min-height: 1.5rem;
            padding: 0 0.5rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: color-mix(in oklab, var(--color-base-100) 82%, transparent);
            color: color-mix(in oklab, var(--color-base-content) 72%, transparent);
        }

        .bangumi-badge-score {
            background: color-mix(in oklab, var(--color-warning) 38%, var(--color-base-100));
            color: var(--color-warning-content);
        }

        .bangumi-badge-progress {
            background: color-mix(in oklab, var(--color-success) 28%, var(--color-base-100));
            color: var(--color-success-content);
        }

        .bangumi-progress {
            display: grid;
            gap: 0.35rem;
            margin: 0.1rem 0;
        }

        .bangumi-progress-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: color-mix(in oklab, var(--color-base-content) 62%, transparent);
        }

        .bangumi-progress-track {
            height: 0.45rem;
            border-radius: 999px;
            overflow: hidden;
            background: color-mix(in oklab, var(--color-base-content) 10%, transparent);
        }

        .bangumi-progress-bar {
            display: block;
            height: 100%;
            width: var(--bangumi-progress, 0%);
            border-radius: inherit;
            background: linear-gradient(90deg, var(--color-success), var(--color-primary));
        }

        .bangumi-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            margin-top: auto;
        }

        .bangumi-tag {
            display: inline-flex;
            align-items: center;
            min-height: 1.4rem;
            padding: 0 0.45rem;
            border-radius: 999px;
            font-size: 0.75rem;
            background: var(--color-base-100);
            color: color-mix(in oklab, var(--color-base-content) 75%, transparent);
        }

        .bangumi-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            min-height: 8rem;
            padding: 1.25rem;
            border-radius: var(--radius-box);
            background: var(--color-base-200);
            color: color-mix(in oklab, var(--color-base-content) 70%, transparent);
            text-align: center;
        }

        .bangumi-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            color: color-mix(in oklab, var(--color-base-content) 70%, transparent);
            font-size: 0.875rem;
        }

        .bangumi-toolbar strong {
            color: var(--color-base-content);
            font-weight: 700;
        }

        .bangumi-toolbar-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.35rem 0.75rem;
        }

        .bangumi-refresh {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 1.8rem;
            padding: 0 0.65rem;
            border: 1px solid color-mix(in oklab, var(--color-base-content) 12%, transparent);
            border-radius: 999px;
            background: color-mix(in oklab, var(--color-base-100) 72%, transparent);
            color: color-mix(in oklab, var(--color-base-content) 62%, transparent);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s ease, border-color 0.2s ease, background-color 0.2s ease, opacity 0.2s ease;
        }

        .bangumi-refresh:hover {
            border-color: color-mix(in oklab, var(--color-primary) 45%, transparent);
            background: color-mix(in oklab, var(--color-primary) 8%, var(--color-base-100));
            color: var(--color-base-content);
        }

        .bangumi-refresh:disabled {
            cursor: wait;
            opacity: 0.65;
        }

        @media (max-width: 520px) {
            .bangumi-grid {
                grid-template-columns: 1fr;
            }

            .bangumi-card {
                grid-template-columns: 82px minmax(0, 1fr);
                min-height: 132px;
            }

            .bangumi-cover {
                width: 82px;
            }

            .bangumi-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .bangumi-refresh {
                width: 100%;
            }
        }
    </style>

    <script data-swup-reload-script>
        (function () {
            var root = document.getElementById('bangumi-watch-list');
            if (!root || root.dataset.initialized === '1') return;
            root.dataset.initialized = '1';

            function escapeHtml(value) {
                return String(value == null ? '' : value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function text(value, fallback) {
                value = value == null ? '' : String(value).trim();
                return value || fallback || '';
            }

            function formatDate(value) {
                if (!value) return '';
                var date = new Date(value);
                if (Number.isNaN(date.getTime())) return '';
                return date.toLocaleDateString('zh-CN', { year: 'numeric', month: '2-digit', day: '2-digit' });
            }

            function getCover(subject) {
                var images = subject && subject.images ? subject.images : {};
                return images.common || images.medium || images.grid || images.large || images.small || '';
            }

            function progressInfo(item, subject, fallbackDone) {
                var total = Number(subject.eps || 0);
                var current = fallbackDone ? total : Number(item.ep_status || 0);
                var percent = 0;

                if (total > 0) {
                    percent = Math.max(0, Math.min(100, Math.round((current / total) * 100)));
                } else if (fallbackDone) {
                    percent = 100;
                }

                return {
                    label: fallbackDone ? '已看完' : '观看进度',
                    value: total > 0 ? current + ' / ' + total + ' 话' : (current > 0 ? '看到 ' + current + ' 话' : '进度未知'),
                    percent: percent
                };
            }

            function buildCard(item, mode) {
                var subject = item.subject || {};
                var title = text(subject.name_cn, subject.name || '未命名条目');
                var subtitle = subject.name_cn && subject.name && subject.name_cn !== subject.name ? subject.name : '';
                var cover = getCover(subject);
                var userRate = Number(item.rate || 0);
                var progress = progressInfo(item, subject, mode === 'watched');
                var updated = formatDate(item.updated_at);
                var meta = updated ? '更新 ' + updated : '';
                var tags = Array.isArray(item.tags) ? item.tags.slice(0, 4) : [];
                var url = subject.id ? 'https://bgm.tv/subject/' + encodeURIComponent(subject.id) : 'https://bgm.tv/';
                var badges = [
                    userRate ? '<span class="bangumi-badge bangumi-badge-score">我的评分 ' + escapeHtml(userRate) + '</span>' : '',
                    mode === 'watched' ? '<span class="bangumi-badge bangumi-badge-progress">已看</span>' : '<span class="bangumi-badge bangumi-badge-progress">在看</span>'
                ].filter(Boolean).join('');

                return [
                    '<a class="bangumi-card" href="' + escapeHtml(url) + '" target="_blank" rel="nofollow noopener noreferrer">',
                    '<figure class="bangumi-cover">',
                    cover ? '<img src="' + escapeHtml(cover) + '" alt="' + escapeHtml(title) + '" loading="lazy">' : '<span class="bangumi-cover-placeholder">BGM</span>',
                    '</figure>',
                    '<div class="bangumi-info">',
                    '<h2 class="bangumi-title">' + escapeHtml(title) + '</h2>',
                    subtitle ? '<p class="bangumi-subtitle">' + escapeHtml(subtitle) + '</p>' : '',
                    badges ? '<div class="bangumi-badges">' + badges + '</div>' : '',
                    '<div class="bangumi-progress">',
                    '<div class="bangumi-progress-label"><span>' + escapeHtml(progress.label) + '</span><span>' + escapeHtml(progress.value) + '</span></div>',
                    '<div class="bangumi-progress-track"><span class="bangumi-progress-bar" style="--bangumi-progress:' + escapeHtml(progress.percent) + '%"></span></div>',
                    '</div>',
                    meta ? '<p class="bangumi-meta">' + escapeHtml(meta) + '</p>' : '',
                    item.comment ? '<p class="bangumi-comment">' + escapeHtml(item.comment) + '</p>' : '',
                    tags.length ? '<div class="bangumi-tags">' + tags.map(function (tag) { return '<span class="bangumi-tag">' + escapeHtml(tag) + '</span>'; }).join('') + '</div>' : '',
                    '</div>',
                    '</a>'
                ].join('');
            }

            function buildSection(title, payload, mode) {
                var items = payload && Array.isArray(payload.items) ? payload.items : [];
                if (!items.length) return '';

                return [
                    '<section class="bangumi-section">',
                    '<div class="bangumi-section-head">',
                    '<h2 class="bangumi-section-title">' + escapeHtml(title) + '</h2>',
                    '<p class="bangumi-section-count">共 ' + escapeHtml(payload.total || items.length) + ' 部</p>',
                    '</div>',
                    '<div class="bangumi-grid">',
                    items.map(function (item) { return buildCard(item, mode); }).join(''),
                    '</div>',
                    '</section>'
                ].join('');
            }

            function render(data) {
                var watching = data.watching || { items: Array.isArray(data.items) ? data.items : [], total: data.total || 0 };
                var watched = data.watched || { items: [], total: 0 };
                var watchingItems = Array.isArray(watching.items) ? watching.items : [];
                var watchedItems = Array.isArray(watched.items) ? watched.items : [];

                if (!watchingItems.length && !watchedItems.length) {
                    root.innerHTML = '<div class="bangumi-status">还没有公开的追番记录。</div>';
                    return;
                }

                root.innerHTML = [
                    '<div class="bangumi-toolbar">',
                    '<div class="bangumi-toolbar-meta">',
                    '<span>在看 <strong>' + escapeHtml(watching.total || watchingItems.length) + '</strong> 部 · 已看 <strong>' + escapeHtml(watched.total || watchedItems.length) + '</strong> 部</span>',
                    data.fetchedAt ? '<span>' + (data.fromCache ? '缓存于 ' : '更新于 ') + escapeHtml(formatDate(data.fetchedAt)) + '</span>' : '',
                    '</div>',
                    '<button class="bangumi-refresh" type="button" data-bangumi-refresh>手动更新</button>',
                    '</div>',
                    buildSection('正在追番', watching, 'watching'),
                    buildSection('已看完', watched, 'watched')
                ].join('');
            }

            function renderError(message) {
                root.innerHTML = '<div class="bangumi-status">' + escapeHtml(message || '追番列表加载失败。') + '</div>';
            }

            function setRefreshState(isLoading) {
                var button = root.querySelector('[data-bangumi-refresh]');
                if (!button) return;
                button.disabled = isLoading;
                button.textContent = isLoading ? '更新中...' : '手动更新';
            }

            function loadBangumi(forceRefresh) {
                var url = root.dataset.api;
                if (forceRefresh) {
                    url += (url.indexOf('?') === -1 ? '?' : '&') + 'refresh=1';
                    setRefreshState(true);
                }

                return fetch(url, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                })
                .then(function (response) {
                    return response.json().catch(function () {
                        throw new Error('接口返回不是有效 JSON');
                    }).then(function (data) {
                        if (!response.ok || data.error) {
                            throw new Error(data.message || '追番列表加载失败');
                        }
                        return data;
                    });
                })
                .then(render)
                .catch(function (error) {
                    renderError(error.message);
                })
                .finally(function () {
                    setRefreshState(false);
                });
            }

            root.addEventListener('click', function (event) {
                var button = event.target.closest('[data-bangumi-refresh]');
                if (!button) return;
                loadBangumi(true);
            });

            loadBangumi(false);
        })();
    </script>
</main>
