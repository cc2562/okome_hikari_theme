<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
// 定义字段配置
return [
    [
        // Text
        'type' => 'Text',
        'name' => 'FeaturedImage',
        'value' => '', // 默认值为空字符串
        'label' => '特色图片',
        'description' => '显示在文章头部的特色图片~',
        'attributes' => [
            'style' => 'width: 100%;' // 自定义样式
        ]
    ],
    [
        // Text
        'type' => 'Text',
        'name' => 'AISummary',
        'value' => '', // 默认值为空字符串
        'label' => 'AI 摘要',
        'description' => '显示在文章头部的 AI 摘要~ <button type="button" id="btn-generate-ai-summary" class="btn btn-s btn-primary" style="margin-left: 10px;">生成摘要</button> <span id="ai-summary-status"></span>',
        'attributes' => [
            'style' => 'width: 100%;' // 自定义样式
        ]
    ],
    [
        'type' => 'Radio',
        'name' => 'showTOC',
        'value' => 'default',
        'label' => '文章目录',
        'description' => '控制本文目录显示。默认跟随全局设置。',
        'options' => [
            'default' => '默认',
            'show' => '显示',
            'hide' => '隐藏'
        ]
    ]
];
