<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE html>
<html lang="zh-CN" class="bg-base-200">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,height=device-height" />
    <?php Get::Components('ThemeColors'); ?>
    <?php TTDF_Hook::do_action('load_head'); ?>
    <link rel="stylesheet" href="<?php get_assets('tailwindcss.css') ?>">
    <link rel="stylesheet" href="<?php get_assets('app.css') ?>">
    <style>
        :root {
            --root-bg: var(--color-base-200);
        }

        html.is-changing .transition-fade {
            transition: opacity 0.25s;
            opacity: 1;
        }

        html.is-animating .transition-fade {
            opacity: 0;
        }

        #swup-loader {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 9999;
        }

        #swup-loader img {
            width: 96px;
            height: 96px;
            object-fit: contain;
        }

        html.is-animating #swup-loader,
        html.is-changing #swup-loader {
            display: flex;
        }

        .swup-progress-bar {
            background-color: var(--color-primary);
        }
    </style>
    <script type="module" src="<?php get_assets('main.js') ?>"></script>
    <?php if (Get::Is('post')): ?>
        <link rel="stylesheet" href="<?php get_assets('highlight.css') ?>">
        <script src="<?php get_assets('highlight.min.js') ?>"></script>
    <?php endif; ?>
    <script src="https://unpkg.com/swup@4"></script>
    <script src="https://unpkg.com/@swup/scripts-plugin@2"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.2/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@swup/scroll-plugin@4"></script>
    <script src="https://unpkg.com/@swup/progress-plugin@3"></script>
    <script src="https://unpkg.com/@swup/forms-plugin@3"></script>
    <script src="<?php get_assets('lazysizes.min.js') ?>" async=""></script>
    <script src="<?php get_assets('medium-zoom.min.js') ?>"></script>
    <link rel="stylesheet" href="<?php get_assets('APlayer.min.css') ?>">
    <script src="<?php get_assets('APlayer.min.js') ?>"></script>
    <style>
        .medium-zoom-overlay,
        .medium-zoom-image--opened {
            z-index: 999;
        }
    </style>
    <script data-swup-ignore-script>
        function TypechoCommentUSE() {
            window.TypechoComment = window.TypechoComment = {
                dom: function(id) {
                    return document.getElementById(id);
                },
                create: function(tag, attr) {
                    var el = document.createElement(tag);
                    for (var key in attr) {
                        el.setAttribute(key, attr[key]);
                    }
                    return el;
                },
                reply: function(cid, coid) {
                    var comment = this.dom(cid),
                        parent = comment.parentNode,
                        response = document.querySelector('[id^="respond-"]') || this.dom('respond') || document.querySelector('#respond'),
                        input = this.dom('comment-parent');
                    if (!response) {
                        var fallbackForm = document.getElementById('comment_form');
                        if (fallbackForm) {
                            response = fallbackForm.closest('[id^="respond-"]') || fallbackForm.parentNode || fallbackForm;
                        }
                    }
                    if (!response) {
                        return false;
                    }
                    var form = response.tagName === 'FORM' ? response : (response.querySelector('form') || document.getElementById('comment_form')),
                        textarea = form ? form.querySelector('textarea') : null;
                    if (null == input) {
                        input = this.create('input', {
                            'type': 'hidden',
                            'name': 'parent',
                            'id': 'comment-parent'
                        });
                        if (form) {
                            form.appendChild(input);
                        }
                    }
                    input.setAttribute('value', coid);
                    if (null == this.dom('comment-form-place-holder')) {
                        var holder = this.create('div', {
                            'id': 'comment-form-place-holder'
                        });
                        if (response.parentNode) {
                            response.parentNode.insertBefore(holder, response);
                        }
                    }
                    comment.appendChild(response);
                    this.dom('cancel-comment-reply-link').style.display = '';
                    if (null != textarea && 'text' == textarea.name) {
                        textarea.focus();
                    }
                    return false;
                },
                cancelReply: function() {
                    var response = document.querySelector('[id^="respond-"]') || this.dom('respond') || document.querySelector('#respond');
                    if (!response) {
                        var fallbackForm = document.getElementById('comment_form');
                        if (fallbackForm) {
                            response = fallbackForm.closest('[id^="respond-"]') || fallbackForm.parentNode || fallbackForm;
                        }
                    }
                    holder = this.dom('comment-form-place-holder'),
                        input = this.dom('comment-parent');
                    if (null != input) {
                        input.parentNode.removeChild(input);
                    }
                    if (null == holder) {
                        return true;
                    }
                    this.dom('cancel-comment-reply-link').style.display = 'none';
                    if (holder.parentNode && response) {
                        holder.parentNode.insertBefore(response, holder);
                    }
                    return false;
                }
            };
        };
    </script>
    <script>
        (function() {
            function ensureHighlightAssets(callback) {
                if (window.hljs) {
                    callback && callback();
                    return;
                }
                var cssHref = '<?php get_assets('highlight.css') ?>';
                if (!document.querySelector('link[href*="highlight.css"]')) {
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = cssHref;
                    document.head.appendChild(link);
                }
                var script = document.createElement('script');
                script.src = '<?php get_assets('highlight.min.js') ?>';
                script.onload = function() {
                    callback && callback();
                };
                document.head.appendChild(script);
            }

            function highlightIfNeeded() {
                var isPostView = !!document.querySelector('#swup article.prose');
                if (!isPostView) return;
                if (window.hljs) {
                    hljs.highlightAll();
                } else {
                    ensureHighlightAssets(function() {
                        hljs.highlightAll();
                    });
                }
            }

            if (window.hljs && <?php echo Get::Is('post') ? 'true' : 'false'; ?>) {
                hljs.highlightAll();
            }

            function initLightbox() {
                var targets = document.querySelectorAll('.lightcover');
                if (!targets || targets.length === 0) return;
                if (typeof mediumZoom !== 'function') return;
                try {
                    if (window.__zoomInstance && typeof window.__zoomInstance.detach === 'function') {
                        window.__zoomInstance.detach();
                    }
                } catch (e) {}
                window.__zoomInstance = mediumZoom(targets, {
                    background: 'rgba(0, 0, 0, 0.4)'
                });
            }

            function initLightboxOnReady() {
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    initLightbox();
                } else {
                    document.addEventListener('DOMContentLoaded', initLightbox, {
                        once: true
                    });
                }
            }

            function initCommentReplyToggle() {
                var list = document.getElementById('comment_list');
                if (!list) return;
                if (list.dataset.replyToggleBound === '1') return;
                list.dataset.replyToggleBound = '1';

                function hideAll() {
                    list.querySelectorAll('.comment-reply').forEach(function(el) {
                        el.classList.add('hidden');
                    });
                }

                function activateFor(target) {
                    var card = target.closest('#comment_list .card');
                    if (!card) return;
                    var btn = card.querySelector('.comment-reply');
                    if (!btn) return;
                    hideAll();
                    btn.classList.remove('hidden');
                }
                list.addEventListener('pointerdown', function(e) {
                    activateFor(e.target);
                });
                list.addEventListener('click', function(e) {
                    activateFor(e.target);
                });
            }




            const swup = new Swup({
                plugins: [
                    new SwupProgressPlugin(),
                    new SwupScrollPlugin({}),
                    new SwupFormsPlugin({
                        formSelector: '#comment_form',
                    }),
                    new SwupScriptsPlugin({
                        optin: true
                    })
                ],
                linkToSelf: 'scroll',
                resolveUrl: (url) => {
                    try {
                        const u = new URL(url, window.location.origin);
                        if (u.pathname === window.location.pathname) {
                            return u.pathname + (u.hash || '');
                        }
                        return u.pathname + (u.search || '') + (u.hash || '');
                    } catch (e) {
                        return url;
                    }
                }
            });


            if (swup.hooks && swup.hooks.on) {
                swup.hooks.on('page:view', function() {
                    highlightIfNeeded();
                    TypechoCommentUSE();
                    initLightbox();
                    initCommentReplyToggle();
                    <?php Get::Options('pjax_Content', true); ?>
                });
                swup.hooks.on('content:replace', function() {
                    highlightIfNeeded();
                    TypechoCommentUSE();
                    initLightbox();
                    initCommentReplyToggle();
                    <?php Get::Options('pjax_Content', true); ?>
                });

            }
            swup.hooks.on('form:submit', (visit, {
                el,
                event
            }) => {
                console.log(el);
                // 1. 创建一个 URL 对象，以便轻松操作参数
                const url = new URL(window.location.href);

                // 2. 清空 searchParams（这将删除所有问号后面的内容）
                url.searchParams.forEach((param, key) => {
                    url.searchParams.delete(key);
                });
                // 或者更简单地，直接设置 search 为空字符串
                // url.search = ''; 

                // 3. 使用 history.replaceState() 更新地址栏，而无需刷新页面
                // 参数分别是：state对象（空对象即可）、页面标题（留空或使用 document.title）、新 URL
                window.history.replaceState({}, document.title, url.pathname + url.hash);
            });

            TypechoCommentUSE();
            highlightIfNeeded();
            initLightboxOnReady();
            initCommentReplyToggle();

        })();
    </script>


</head>

<body class="bg-base-200 px-2 sm:px-8">
    <div id="app">
        <div class="drawer">
            <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex flex-col items-stretch justify-start">
                <header class="fixed top-4 sm:top-6 inset-x-0 z-30 mx-2">
                    <div class="container shadow-sm mx-auto max-w-5xl px-4 py-4 flex items-center justify-start space-x-4 bg-base-100 rounded-box backdrop-filter" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background-color: color-mix(in oklab, var(--color-base-100) 80%, transparent);">
                        <label for="my-drawer-3" class="btn drawer-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </label>
                        <a href="<?php get_site_url(); ?>" class="text-lg sm:text-xl font-semibold tracking-tight"><?php get_site_name(); ?></a>
                    </div>
                </header>
            </div>
            <div class="drawer-side z-50">
                <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay blur-sm z-40" style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"></label>
                <ul class="menu min-h-full rounded-br-box rounded-tr-box bg-transparent z-50 sm:w-100 w-3/4 sm:mt-16 sm:ml-8">
                    <!-- Sidebar content here -->
                    <div class="flex flex-row space-x-4 w-full sm:h-28 h-30 ">
                        <img
                            class="mask mask-squircle h-full w-30 sm:w-28 object-cover"
                            src=<?php Get::Options('sideBarImg', true); ?> />
                        <div class="card bg-info text-primary-content flex-1">
                            <div class="card-body h-full">
                                <h2 class="card-title text-info-content"><?php get_site_name(); ?></h2>
                                <p class="hidden sm:block"><?php Get::Options('sideBarDesc', true); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-base-100 text-primary-100 w-full mt-4">
                        <div class="card-body">
                            <div class="flex flex-row  w-full h-6 items-end space-x-2 mb-2">
                                <h3 class="text-primary-content font-semibold text-2xl">导航</h3>
                                <!--h5 class="text-sm">Share the world</h5!-->
                            </div>
                            <li><a href="<?php get_site_url(); ?>">首页</a></li>
                            <li><a href="<?php Get::Options('friends', true); ?>">友情链接</a></li>
                            <li><a href="<?php Get::Options('aboutLink', true); ?>">关于我</a></li>
                        </div>
                    </div>
                    <div class="card bg-secondary text-primary-content w-full mt-4">
                        <div class="card-body">
                            <div class="flex flex-row  w-full h-6 items-end space-x-2 mb-2">
                                <h3 class="text-primary-content font-semibold text-2xl">分类</h3>
                            </div>

                            <?php $this->widget('Widget_Metas_Category_List')
                                ->parse('<li><a href="{permalink}">{name}</a></li>'); ?>
                        </div>
                    </div>

                </ul>
            </div>
        </div>
    </div>
    <div id="swup-loader" class="flex flex-col gap-4">
        <img src=<?php Get::Options('loadingImg', true); ?> alt="" />
        <span class="loading loading-spinner text-primary"></span>
    </div>
    <div id="swup" class="transition-fade pt-28 sm:pt-32">
        <script>
            (function() {
                const toggle = document.getElementById('my-drawer-3');
                const menu = document.querySelector('.drawer-side .menu');
                if (!toggle || !menu) return;
                menu.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return;
                    if (e.button === 0 && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                        toggle.checked = false;
                    }
                });
            })();
        </script>