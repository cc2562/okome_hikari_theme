(function () {
    function TypechoCommentUSE() {
        window.TypechoComment = {
            dom: function (id) {
                return document.getElementById(id);
            },
            create: function (tag, attr) {
                var el = document.createElement(tag);
                for (var key in attr) {
                    el.setAttribute(key, attr[key]);
                }
                return el;
            },
            reply: function (cid, coid) {
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
            cancelReply: function () {
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
    }
    function getAssetsBaseAndQuery() {
        var candidates = [];
        document.querySelectorAll('link[href*="/assets/"]').forEach(function (l) {
            candidates.push(l.href);
        });
        document.querySelectorAll('script[src*="/assets/"]').forEach(function (s) {
            candidates.push(s.src);
        });
        if (candidates.length === 0) {
            return {
                base: '',
                query: ''
            };
        }
        var u = new URL(candidates[0]);
        var p = u.pathname;
        var idx = p.lastIndexOf('/');
        var base = u.origin + p.slice(0, idx + 1);
        return {
            base: base,
            query: u.search || ''
        };
    }
    function ensureHighlightAssets(callback) {
        if (window.hljs) {
            if (callback) callback();
            return;
        }
        var info = getAssetsBaseAndQuery();
        var cssHref = info.base + 'highlight.css' + info.query;
        if (!document.querySelector('link[href*="highlight.css"]')) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = cssHref;
            document.head.appendChild(link);
        }
        var script = document.createElement('script');
        script.src = info.base + 'highlight.min.js' + info.query;
        script.onload = function () {
            if (callback) callback();
        };
        document.head.appendChild(script);
    }
    function highlightIfNeeded() {
        var isPostView = !!document.querySelector('#swup article.prose');
        if (!isPostView) return;
        if (window.hljs) {
            hljs.highlightAll();
        } else {
            ensureHighlightAssets(function () {
                hljs.highlightAll();
            });
        }
    }
    function initLightbox() {
        var targets = document.querySelectorAll('.lightcover');
        if (!targets || targets.length === 0) return;
        if (typeof mediumZoom !== 'function') return;
        try {
            if (window.__zoomInstance && typeof window.__zoomInstance.detach === 'function') {
                window.__zoomInstance.detach();
            }
        } catch (e) { }
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
            list.querySelectorAll('.comment-reply').forEach(function (el) {
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
        list.addEventListener('pointerdown',
            function (e) {
                activateFor(e.target);
            });
        list.addEventListener('click',
            function (e) {
                activateFor(e.target);
            });
    }
    function bindDrawerAutoClose() {
        var toggle = document.getElementById('my-drawer-3');
        var menu = document.querySelector('.drawer-side .menu');
        if (!toggle || !menu) return;
        if (menu.dataset.drawerBound === '1') return;
        menu.dataset.drawerBound = '1';
        menu.addEventListener('click',
            function (e) {
                var link = e.target.closest('a');
                if (!link) return;
                if (e.button === 0 && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    toggle.checked = false;
                }
            });
    }
    function isPostView() {
        return !!document.querySelector('#swup article.prose');
    }
    function getPostTitle() {
        var el = document.querySelector('#swup article.prose h1');
        return el ? el.textContent.trim() : '';
    }
    function initHeaderTitleSwap() {
        var titleEl = document.querySelector('header a .header-title-text');
        if (!titleEl) return;
        var siteTitle = (titleEl.dataset && titleEl.dataset.siteTitle ? titleEl.dataset.siteTitle : titleEl.getAttribute('data-site-title')) || titleEl.textContent.trim();
        var postTitle = getPostTitle();
        titleEl.textContent = siteTitle;
        var lastY = window.scrollY;
        var swapping = false;
        function swapTo(text) {
            if (swapping) return;
            swapping = true;
            titleEl.classList.add('is-swapping');
            setTimeout(function () {
                titleEl.textContent = text;
                titleEl.classList.remove('is-swapping');
                swapping = false;
            },
                160);
        }
        function onScroll() {
            var y = window.scrollY;
            var down = y > lastY;
            lastY = y;
            if (!isPostView()) {
                if (titleEl.textContent.trim() !== siteTitle) titleEl.textContent = siteTitle;
                return;
            }
            if (down && y > 50 && postTitle) {
                if (titleEl.textContent.trim() !== postTitle) swapTo(postTitle);
            } else {
                if (titleEl.textContent.trim() !== siteTitle) swapTo(siteTitle);
            }
        }
        if (window.__headerTitleScrollHandler) {
            window.removeEventListener('scroll', window.__headerTitleScrollHandler);
        }
        window.__headerTitleScrollHandler = onScroll;
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
    }
    var swup = new Swup({
        plugins: [new SwupProgressPlugin(), new SwupScrollPlugin({}), new SwupFormsPlugin({
            formSelector: '#comment_form'
        }), new SwupScriptsPlugin({
            optin: true
        })],
        linkToSelf: 'scroll',
        resolveUrl: function (url) {
            try {
                var u = new URL(url, window.location.origin);
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
        swup.hooks.on('page:view',
            function () {
                highlightIfNeeded();
                TypechoCommentUSE();
                initLightbox();
                initCommentReplyToggle();
                initHeaderTitleSwap();
                if (typeof window.__onPjaxContent === 'function') {
                    window.__onPjaxContent();
                }
            });
        swup.hooks.on('content:replace',
            function () {
                highlightIfNeeded();
                TypechoCommentUSE();
                initLightbox();
                initCommentReplyToggle();
                initHeaderTitleSwap();
                if (typeof window.__onPjaxContent === 'function') {
                    window.__onPjaxContent();
                }
            });
    }
    swup.hooks.on('form:submit',
        function (visit, ctx) {
            var url = new URL(window.location.href);
            url.searchParams.forEach(function (param, key) {
                url.searchParams.delete(key);
            });
            window.history.replaceState({},
                document.title, url.pathname + url.hash);
        });
    TypechoCommentUSE();
    highlightIfNeeded();
    initLightboxOnReady();
    initCommentReplyToggle();
    document.addEventListener('DOMContentLoaded', bindDrawerAutoClose);
    initHeaderTitleSwap();
})();
