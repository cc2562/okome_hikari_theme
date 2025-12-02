<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<main>
    <div class="mx-auto w-full max-w-full sm:max-w-5xl sm:px-4 flex flex-col gap-6 ">
        <div class="card bg-base-100 p-4 md:p-8 shadow-sm">
            <article class="prose max-w-none">
                <?php $featuredImage = get_fields('FeaturedImage'); ?>
                <?php if ($featuredImage) : ?>
                    <img data-src="<?php echo $featuredImage; ?>" src="<?php Get::Options('loadingImg', true); ?>" alt="特色图片" class="lightcover w-full h-64 md:h-120 object-cover blur-up lazyautosizes lazyload">
                <?php endif; ?>
                <h1 class="text-3xl font-bold mb-2"><?php GetPost::Title(); ?></h1>
                <div class="text-sm text-gray-500 mb-6">
                    <?php GetPost::FormatDate(); ?>
                    · <?php GetPost::Category(', ', true, ''); ?>
                    · <?php GetPost::Tags(', ', true, ''); ?>
                </div>


                <div class="mt-6">
                    <?php $aiSummary = get_fields('AISummary'); ?>
                    <?php if ($aiSummary) : ?>
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
                    <?php if (Get::Options('enablePostInfo', false) == '1') : ?>
                        <div class="relative bg-base-200 rounded-box p-6 mb-8 mt-4 overflow-hidden group">
                            <!-- Content -->
                            <div class="relative z-10">
                                <div class="text-xl font-bold mb-2 text-base-content"><?php GetPost::Title(); ?></div>
                                <a href="<?php GetPost::Permalink(); ?>" class="text-sm text-primary break-all hover:underline mb-6 block">
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
                                            <a href="<?php Get::Options('licenseLink', true); ?>" target="_blank" rel="nofollow noopener noreferrer" class="text-primary hover:underline inline-flex items-center gap-1">
                                                <?php Get::Options('licenseName', true); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CC Icon Watermark -->
                            <?php if (Get::Options('enableCCLogo', false) == '1') : ?>
                                <div class="absolute -bottom-6 -right-6 opacity-[0.08] text-[10rem] leading-none font-black select-none pointer-events-none transition-opacity duration-300 group-hover:opacity-[0.12]">
                                    CC
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                







            </article>
        </div>

    </div>
    <div class=" mt-4">
    </div>

</main>