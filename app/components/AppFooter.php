<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
</div>
<footer>
    <div class="container mx-auto max-w-3xl px-4 py-6 text-sm text-gray-500 dark:text-gray-400">
        <p>© <?php echo date('Y'); ?> <?php get_site_name(); ?></p>
    </div>

</footer>
<div id="backToTopFab" class="fab fixed bottom-6 right-6 z-50 back-to-top-fab">
    <button id="backToTopTrigger" type="button" aria-label="回到顶部" class="btn btn-lg btn-circle btn-base bg-base-300">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V3M3 12l9-9 9 9" />
        </svg>
    </button>
</div>
<script>
    (function() {
        var fab = document.getElementById('backToTopFab');
        if (!fab) return;
        var trigger = document.getElementById('backToTopTrigger');
        var scrollTop = function() {
            var start = window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;
            var supportsSmooth = 'scrollBehavior' in document.documentElement.style;
            if (supportsSmooth) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
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
        };
        var updateVisibility = function() {
            var show = (window.scrollY || document.documentElement.scrollTop || 0) > 200;
            if (show) {
                fab.classList.add('is-visible');
            } else {
                fab.classList.remove('is-visible');
            }
        };
        if (trigger) {
            trigger.addEventListener('click', scrollTop);
        }
        updateVisibility();
        window.addEventListener('scroll', updateVisibility, {
            passive: true
        });
    })();
</script>
</div>
<?php TTDF_Hook::do_action('load_foot'); ?>
</body>

</html>