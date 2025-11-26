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
                    <?php
                    echo GetPost::Content(false);
                    ?>
                </div>






            </article>
        </div>

    </div>
    <div class="mt-4"></div>

</main>