<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<main>
    <div class="mx-auto w-full max-w-full sm:max-w-5xl sm:px-4 flex flex-col gap-6 ">
        <div class="card bg-base-100 p-4 md:p-8 shadow-sm">
            <article class="prose max-w-none">
                <h1 class="text-3xl font-bold mb-2">友情链接</h1>
                <div class="text-sm text-gray-500 mb-6">
                    这里是我的好朋友们~
                </div>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <?php if (isset($this->options->plugins['activated']['Links'])) : ?>
                        <?php
                        Links_Plugin::output('
				<a target="_blank" href="{url}" class="link-wrap block">
					<div class="card card-side bg-base-200 p-4 flex flex-row items-center gap-4 m-2 w-auto transition  duration-300 ease-in-out hover:scale-110 active:scale-110 active:bg-primary hover:bg-primary"> 
						<figure>
                        <img class="mask mask-squircle w-20 h-20 hover:animate-spin object-cover" src="{image}" alt="{name}"/>
                        </figure>
						<div class="flex flex-col">
							<p class="text-lg font-bold text-base-content">{name}</p>
							<p class="text-sm text-gray-500">{description}</p>
						</div>
					</div>
				</a>', 0);
                        ?>
                    <?php endif; ?>
                </div>




            </article>
        </div>

    </div>
    <div class="mt-4"></div>

</main>