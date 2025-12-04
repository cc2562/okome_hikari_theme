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
    function updateTitleOverflowMask() {
        var el = document.querySelector('header .header-title-text');
        if (!el) return;
        var isOverflow = el.scrollWidth > (el.clientWidth + 1);
        if (isOverflow) {
            el.classList.add('is-overflowing');
        } else {
            el.classList.remove('is-overflowing');
        }
    }
    function scrollTopSmooth() {
        var start = window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;
        var supportsSmooth = 'scrollBehavior' in document.documentElement.style;
        if (supportsSmooth) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }
        var duration = 300;
        var startTime = null;
        function step(ts) {
            if (!startTime) startTime = ts;
            var progress = Math.min((ts - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var pos = Math.round(start * (1 - eased));
            window.scrollTo(0, pos);
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    function initHeaderHoverTitle() {
        var container = document.querySelector('header .header-glass');
        var titleEl = document.querySelector('header .header-title-text');
        if (!container || !titleEl) return;
        if (container.dataset.hoverTitleBound === '1') return;
        container.dataset.hoverTitleBound = '1';
        var siteTitle = (titleEl.dataset && titleEl.dataset.siteTitle ? titleEl.dataset.siteTitle : titleEl.getAttribute('data-site-title')) || titleEl.textContent.trim();
        function desiredTitle() {
            var postTitle = getPostTitle();
            var y = window.scrollY;
            var showPost = isPostView() && y > 50 && postTitle;
            return showPost ? postTitle : siteTitle;
        }
        function swapText(text) {
            titleEl.classList.add('is-swapping');
            setTimeout(function () {
                titleEl.textContent = text;
                titleEl.classList.remove('is-swapping');
                updateTitleOverflowMask();
            }, 160);
        }
        container.addEventListener('pointerenter', function () {
            var y = window.scrollY || document.documentElement.scrollTop || 0;
            if (y > 0) {
                window.__headerHoverActive = true;
                swapText('回到顶部');
            }
        });
        container.addEventListener('pointerleave', function () {
            if (!window.__headerHoverActive) return;
            window.__headerHoverActive = false;
            swapText(desiredTitle());
        });
    }
    function initHeaderClickNavigate() {
        var container = document.querySelector('header .header-glass');
        if (!container) return;
        if (container.dataset.clickNavBound === '1') return;
        container.dataset.clickNavBound = '1';
        var homeHref = container.dataset.homeUrl || '/';
        container.addEventListener('click', function (e) {
            var target = e.target;
            if (target.closest('.drawer-button')) return;
            var y = window.scrollY || document.documentElement.scrollTop || 0;
            if (y > 0) {
                scrollTopSmooth();
            } else {
                try {
                    if (typeof swup !== 'undefined' && swup && typeof swup.navigate === 'function') {
                        swup.navigate(homeHref);
                    } else if (typeof swup !== 'undefined' && swup && typeof swup.loadPage === 'function') {
                        swup.loadPage({ url: homeHref });
                    } else {
                        window.location.href = homeHref;
                    }
                } catch (err) {
                    window.location.href = homeHref;
                }
            }
        });
    }
    function initHeaderTitleSwap() {
        var titleEl = document.querySelector('header .header-title-text');
        if (!titleEl) return;
        var siteTitle = (titleEl.dataset && titleEl.dataset.siteTitle ? titleEl.dataset.siteTitle : titleEl.getAttribute('data-site-title')) || titleEl.textContent.trim();
        var postTitle = getPostTitle();
        titleEl.textContent = siteTitle;
        updateTitleOverflowMask();
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
                updateTitleOverflowMask();
            }, 160);
        }
        function onScroll() {
            var y = window.scrollY;
            var down = y > lastY;
            lastY = y;
            if (window.__headerHoverActive) {
                return;
            }
            if (!isPostView()) {
                if (titleEl.textContent.trim() !== siteTitle) titleEl.textContent = siteTitle;
                updateTitleOverflowMask();
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
        if (window.__titleOverflowResizeHandler) {
            window.removeEventListener('resize', window.__titleOverflowResizeHandler);
        }
        window.__titleOverflowResizeHandler = function () { updateTitleOverflowMask(); };
        window.addEventListener('resize', window.__titleOverflowResizeHandler);
    }
    function initReadingProgress() {
        var track = document.querySelector('header .header-progress');
        var bar = track ? track.querySelector('.header-progress__bar') : null;
        if (!track || !bar) return;
        function unbind() {
            if (window.__readingProgressScrollHandler) {
                window.removeEventListener('scroll', window.__readingProgressScrollHandler);
                window.__readingProgressScrollHandler = null;
            }
            if (window.__readingProgressResizeHandler) {
                window.removeEventListener('resize', window.__readingProgressResizeHandler);
                window.__readingProgressResizeHandler = null;
            }
        }
        function bind() {
            unbind();
            var post = document.querySelector('#swup article.prose');
            if (!post) {
                track.classList.remove('active');
                bar.style.width = '0%';
                return;
            }
            track.classList.add('active');
            var rect = post.getBoundingClientRect();
            var start = rect.top + window.scrollY;
            var end = rect.bottom + window.scrollY - window.innerHeight;
            if (end <= start) end = start + 1;
            function update() {
                var y = window.scrollY;
                var p = (y - start) / (end - start);
                if (p < 0) p = 0;
                if (p > 1) p = 1;
                bar.style.width = (p * 100).toFixed(2) + '%';
                var hideThreshold = 24;
                if (y > end + hideThreshold) {
                    track.classList.remove('active');
                } else {
                    track.classList.add('active');
                }
            }
            update();
            window.__readingProgressScrollHandler = function () { update(); };
            window.addEventListener('scroll', window.__readingProgressScrollHandler, { passive: true });
            window.__readingProgressResizeHandler = function () { bind(); };
            window.addEventListener('resize', window.__readingProgressResizeHandler);
        }
        bind();
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
                updateTitleOverflowMask();
                initReadingProgress();
                initHeaderClickNavigate();
                initHeaderHoverTitle();
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
                updateTitleOverflowMask();
                initReadingProgress();
                initHeaderClickNavigate();
                initHeaderHoverTitle();
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
    updateTitleOverflowMask();
    initReadingProgress();
    initHeaderClickNavigate();
    initHeaderHoverTitle();
})();
