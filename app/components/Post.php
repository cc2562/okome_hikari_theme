<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
?>
<main>
    <?php
    $globalTOC = Get::Options('enableTOC');
    $postTOC = get_fields('showTOC');
    $showTOC = ($postTOC === 'show') || ($postTOC !== 'hide' && $globalTOC == '1');
    ?>
    <div class="relative mx-auto w-full max-w-full sm:max-w-5xl sm:px-4 flex flex-col gap-6">

        <!-- Main Content -->
        <div class="w-full flex flex-col gap-6">
            <div class="card bg-base-100 p-4 md:p-8 shadow-sm">
                <article class="prose max-w-none" id="article-content">
                    <?php $featuredImage = get_fields('FeaturedImage'); ?>
                    <?php if ($featuredImage): ?>
                        <img data-src="<?php echo $featuredImage; ?>" src="<?php Get::Options('loadingImg', true); ?>"
                            alt="特色图片" class="lightcover w-full h-64 md:h-120 object-cover blur-up lazyautosizes lazyload">
                    <?php endif; ?>
                    <h1 class="text-3xl font-bold mb-2"><?php GetPost::Title(); ?></h1>
                    <div class="text-sm text-gray-500 mb-6">
                        <?php GetPost::FormatDate(); ?>
                        · <?php GetPost::Category(', ', true, ''); ?>
                        · <?php GetPost::Tags(', ', true, ''); ?>
                    </div>


                    <div class="mt-6">
                        <?php $aiSummary = get_fields('AISummary'); ?>
                        <?php if ($aiSummary): ?>
                            <div class="bg-base-200 card p-4  mb-2 shadow-sm">
                                <p class="flex flex-row items-center  gap-2">
                                    <span class="badge"><?php Get::Options('ai_show_badge', true); ?></span>
                                    <title class="card-title">摘要</title>
                                </p>
                                <div class="bg-base-100 p-4 mt-2 card md:p-8">
                                    <p><?php echo GetPost::applyLazyloadToImages($aiSummary); ?></p>
                                </div>
                            </div>

                        <?php endif; ?>
                        <?php
                        echo GetPost::Content(false);
                        ?>
                    </div>
                    <?php if ($this->is('post')): ?>
                        <?php if (Get::Options('enablePostInfo', false) == '1'): ?>
                            <div class="relative bg-base-200 rounded-box p-6 mb-8 mt-4 overflow-hidden group">
                                <!-- Content -->
                                <div class="relative z-10">
                                    <div class="text-xl font-bold mb-2 text-base-content"><?php GetPost::Title(); ?></div>
                                    <a href="<?php GetPost::Permalink(); ?>"
                                        class="text-sm text-primary break-all hover:underline mb-6 block">
                                        <?php GetPost::Permalink(); ?>
                                    </a>

                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <div class="text-base-content/60 mb-1">本文作者</div>
                                            <div class="font-medium text-base-content"><?php GetPost::Author(); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-base-content/60 mb-1">发布时间</div>
                                            <div class="font-medium text-base-content"><?php GetPost::FormatDate(); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-base-content/60 mb-1">许可协议</div>
                                            <div>
                                                <a href="<?php Get::Options('licenseLink', true); ?>" target="_blank"
                                                    rel="nofollow noopener noreferrer"
                                                    class="text-primary hover:underline inline-flex items-center gap-1">
                                                    <?php Get::Options('licenseName', true); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CC Icon Watermark -->
                                <?php if (Get::Options('enableCCLogo', false) == '1'): ?>
                                    <div
                                        class="absolute -bottom-6 -right-6 opacity-[0.08] text-[10rem] leading-none font-black select-none pointer-events-none transition-opacity duration-300 group-hover:opacity-[0.12]">
                                        CC
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </article>
            </div>
        </div>

        <!-- Sidebar (Desktop TOC) -->
        <?php if ($showTOC): ?>
            <div class="hidden xl:block absolute left-full top-0 h-full ml-10 w-64 text-sm">
                <div class="sticky top-6">
                    <div class="card bg-base-100 p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-base-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span class="font-bold text-lg">目录</span>
                        </div>
                        <nav id="desktop-toc-content" class="max-h-[calc(100vh-10rem)] overflow-y-auto custom-scrollbar">
                            <!-- TOC Content Generated by JS -->
                        </nav>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Mobile TOC Trigger -->
    <?php if ($showTOC): ?>
        <div id="mobile-toc-fab" class="fixed bottom-28 right-6 z-50">
            <button id="mobile-toc-btn" type="button" aria-label="文章目录" class="btn btn-lg btn-circle btn-primary shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </button>
        </div>

        <!-- Mobile TOC Drawer -->
        <div id="mobile-toc-drawer" class="fixed inset-0 z-[100] hidden">
            <!-- Backdrop -->
            <div id="mobile-toc-backdrop" class="absolute inset-0 bg-black/50 transition-opacity opacity-0"></div>

            <!-- Drawer Content -->
            <div id="mobile-toc-content-wrapper"
                class="absolute right-0 bottom-0 top-0 w-3/4 max-w-sm bg-base-100 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
                <div class="p-4 border-b border-base-200 flex justify-between items-center bg-base-100">
                    <h3 class="font-bold text-lg">目录</h3>
                    <button id="close-mobile-toc" class="btn btn-sm btn-ghost btn-square">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="mobile-toc-list" class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                    <!-- Mobile TOC Content Generated by JS -->
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script data-swup-reload-script>
        (function () {
            // --- 1. Generate TOC ---
            const article = document.getElementById('article-content');
            if (!article) return;

            const headers = article.querySelectorAll('h2, h3');
            if (headers.length === 0) {
                // Hide TOC containers if no headers
                const desktopTocContainer = document.querySelector('.xl\\:block'); // Updated selector
                if (desktopTocContainer) desktopTocContainer.style.display = 'none';

                const mobileTocFab = document.getElementById('mobile-toc-fab');
                if (mobileTocFab) mobileTocFab.style.display = 'none';

                return;
            }

            const buildToc = (containerId) => {
                const container = document.getElementById(containerId);
                if (!container) return;

                let ul = document.createElement('ul');
                ul.className = 'menumenu-sm w-full';

                headers.forEach((header, index) => {
                    if (!header.id) header.id = 'section-' + index;

                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = '#' + header.id;
                    a.textContent = header.textContent;
                    a.className = 'toc-link block py-2 px-3 rounded-lg hover:bg-base-200 transition-colors text-base-content/70 hover:text-primary';

                    // Indentation for h3
                    if (header.tagName.toLowerCase() === 'h3') {
                        a.classList.add('ml-4', 'text-xs');
                    } else {
                        a.classList.add('font-medium');
                    }

                    // Add scroll margin to header for sticky offset
                    header.classList.add('scroll-mt-32');

                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        // Smooth scroll
                        const targetId = a.getAttribute('href').substring(1);
                        const targetElement = document.getElementById(targetId);
                        if (targetElement) {
                            targetElement.scrollIntoView({ behavior: 'smooth' });

                            // If mobile, close drawer
                            if (containerId === 'mobile-toc-list') {
                                closeMobileToc();
                            }
                        }
                    });

                    li.appendChild(a);
                    ul.appendChild(li);
                });
                container.appendChild(ul);
            };

            buildToc('desktop-toc-content');
            buildToc('mobile-toc-list');

            // --- 2. Scroll Spy ---
            const tocLinks = document.querySelectorAll('.toc-link');
            const observerOptions = {
                root: null,
                rootMargin: '-100px 0px -60% 0px', // Trigger point near top
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.id;
                        tocLinks.forEach(link => {
                            if (link.getAttribute('href') === '#' + id) {
                                link.classList.add('bg-primary', 'text-primary-content', 'hover:bg-primary-focus', 'hover:text-primary-content');
                                link.classList.remove('text-base-content/70', 'hover:bg-base-200', 'hover:text-primary'); // Remove default
                            } else {
                                link.classList.remove('bg-primary', 'text-primary-content', 'hover:bg-primary-focus', 'hover:text-primary-content');
                                link.classList.add('text-base-content/70', 'hover:bg-base-200', 'hover:text-primary'); // Restore default
                            }
                        });
                    }
                });
            }, observerOptions);

            headers.forEach(header => observer.observe(header));


            // --- 3. Mobile Drawer Controls ---
            const fab = document.getElementById('mobile-toc-fab');
            const tocBtn = document.getElementById('mobile-toc-btn');
            const drawer = document.getElementById('mobile-toc-drawer');
            const backdrop = document.getElementById('mobile-toc-backdrop');
            const contentWrapper = document.getElementById('mobile-toc-content-wrapper');
            const closeBtn = document.getElementById('close-mobile-toc');

            function openMobileToc() {
                drawer.classList.remove('hidden');
                // Allow display:none to apply before adding opacity classes
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    contentWrapper.classList.remove('translate-x-full');
                }, 10);
                document.body.style.overflow = 'hidden'; // Lock scroll
            }

            function closeMobileToc() {
                backdrop.classList.add('opacity-0');
                contentWrapper.classList.add('translate-x-full');
                setTimeout(() => {
                    drawer.classList.add('hidden');
                    document.body.style.overflow = ''; // Unlock scroll
                }, 300);
            }

            if (tocBtn) tocBtn.addEventListener('click', openMobileToc);
            if (closeBtn) closeBtn.addEventListener('click', closeMobileToc);
            if (backdrop) backdrop.addEventListener('click', closeMobileToc);

            // --- 4. FAB Visibility (Like Back To Top) ---
            // Hide/Show FAB based on scroll, similar to back-to-top logic if desired.
            // For now, let's keep it visible on mobile screens always if headers exist.
            // Use ResizeObserver or CSS media queries to hide FAB on desktop.
            // It's already handled by CSS media queries on the grid (FAB is fixed... wait, FAB needs hiding on desktop)
            // Let's add CSS for FAB hiding on desktop in style attribute or check width

            function handleResize() {
                if (window.innerWidth >= 1280) { // xl breakpoint
                    fab.style.display = 'none';
                } else {
                    if (headers.length > 0) fab.style.display = 'block';
                }
            }
            // Fix for Swup: Remove previous listener if exists to prevent duplicates
            if (window.tocResizeHandler) {
                window.removeEventListener('resize', window.tocResizeHandler);
            }
            window.tocResizeHandler = handleResize;
            window.addEventListener('resize', window.tocResizeHandler);

            handleResize(); // Initial check

        })();
    </script>

</main>