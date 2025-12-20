<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
?>
<main>
    <div class="container mx-auto max-w-5xl px-4 flex flex-col gap-6">
        <?php if (Get::Total() > 0): ?>
            <?php while (Get::Next()): ?>
                <article class="w-full">
                    <div
                        class="card flex flex-col bg-base-100 w-full shadow-sm hover:scale-101 active:scale-101 transition-transform duration-300 p-2">
                        <a href="<?php GetPost::Permalink(); ?>">
                            <?php if (GetPost::getFeaturedImage() !== 'none'): ?>
                                <div class="card-image rounded-box  aspect-3/2  min-w-10 max-h-80 md:max-h-70 w-full">
                                    <img class="rounded-box  aspect-3/2  object-cover min-w-10 max-h-80 md:max-h-70 w-full lazyautosizes lazyload blur-up "
                                        data-src="<?php echo GetPost::getFeaturedImage(); ?>"
                                        src="<?php Get::Options('loadingImg', true); ?>" alt="<?php GetPost::Title(); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <a class=" text-xl card-title"
                                    href="<?php GetPost::Permalink(); ?>"><?php GetPost::Title(); ?></a>
                                <?php $aiSummary = get_fields('AISummary'); ?>
                                <?php if ($aiSummary): ?>
                                    <p class="flex flex-col items-start  gap-2">
                                        <?php echo mb_substr($aiSummary, 0, 100, 'UTF-8'); ?>...
                                        <span class="badge"><?php Get::Options('ai_show_badge', true); ?> AI摘要</span>
                                    </p>
                                <?php else: ?>
                                    <p><?php GetPost::Excerpt(100, true); ?>...</p>
                                <?php endif; ?>

                            </div>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php
            $nowpage = $currentPage = Get::CurrentPage();
            $total = Get::Total();
            $pageSize = Get::PageSize();
            $totalPages = ceil($total / $pageSize);
            ?>
            <div class="flex justify-between gap-2">
                <?php if ($nowpage > 1): ?>
                    <a class="btn btn-primary " href="<?php Get::PageLink('PrevPage', 'prev', true); ?>">上一页</a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
                <?php if ($nowpage < $totalPages): ?>
                    <a class="btn btn-primary " href="<?php Get::PageLink('NextPage', 'next', true); ?>">下一页</a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">暂无文章</p>
        <?php endif; ?>
    </div>

</main>