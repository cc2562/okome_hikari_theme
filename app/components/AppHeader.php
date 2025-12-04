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
    <link rel="stylesheet" href="<?php get_assets('OwO.min.css') ?>">

    <script type="module" src="<?php get_assets('main.js') ?>"></script>
    <?php if (Get::Is('post')): ?>
        <link rel="stylesheet" href="<?php get_assets('highlight.css') ?>">
        <script src="<?php get_assets('highlight.min.js') ?>"></script>
    <?php endif; ?>
    <script src="<?php get_assets('Swup.umd.js') ?>"></script>
    <script src="<?php get_assets('swup.script-plugins.js') ?>"></script>
    <script defer src="<?php get_assets('alpinejs.cdn.min.js') ?>"></script>
    <script src="<?php get_assets('swup.scroll-plugin.js') ?>"></script>
    <script src="<?php get_assets('swup.progress-plugin.js') ?>"></script>
    <script src="<?php get_assets('swup.forms-plugin.js') ?>"></script>
    <script src="<?php get_assets('lazysizes.min.js') ?>" async=""></script>
    <script src="<?php get_assets('medium-zoom.min.js') ?>"></script>
    <link rel="stylesheet" href="<?php get_assets('APlayer.min.css') ?>">
    <script src="<?php get_assets('OwO.min.js') ?>"></script>
    <script src="<?php get_assets('APlayer.min.js') ?>"></script>
    <link rel="stylesheet" href="<?php get_assets('header.css') ?>">
    <script>
        window.__onPjaxContent = function() {
            <?php Get::Options('pjax_Content', true); ?>
        }
    </script>
    <script src="<?php get_assets('header.js') ?>" data-swup-ignore-script defer></script>



</head>

<body class="bg-base-200 px-2 sm:px-8">
    <div id="app">
        <div class="drawer">
            <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex flex-col items-stretch justify-start">
                <header class="fixed top-4 sm:top-6 inset-x-0 z-30 mx-2">
                    <div class="container shadow-sm mx-auto max-w-5xl px-4 py-4 flex items-center justify-start space-x-4 bg-base-100 rounded-box backdrop-filter header-glass" data-home-url="<?php get_site_url(); ?>">
                        <label for="my-drawer-3" class="btn drawer-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </label>
                        <button type="button" class="header-title-btn text-lg sm:text-xl font-semibold tracking-tight"><span class="header-title-text" data-site-title="<?php get_site_name(); ?>"><?php get_site_name(); ?></span></button>
                        <div class="header-progress">
                            <div class="header-progress__bar"></div>
                        </div>
                    </div>
                </header>
            </div>
            <div class="drawer-side z-50">
                <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay blur-sm z-40 overlay-glass"></label>
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
                                <h3 class=" font-semibold text-2xl">导航</h3>
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
                                <h3 class="font-semibold text-2xl">分类</h3>
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