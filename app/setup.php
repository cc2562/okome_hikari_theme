<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 主题设置
 * Html可以使用element组件
 * https://element-plus.org/zh-CN/component/overview
 */
return [
    'Rice-Options' => [
        'title' => '主题设置',
        'fields' => [
            [
                'type' => 'Text',
                'name' => 'sideBarImg',
                'value' => get_assets('Emoji/bilibili/10001.png', false, false),
                'label' => '侧边栏图片',
                'description' => '侧边栏图片'
            ],
            [
                'type' => 'Text',
                'name' => 'sideBarDesc',
                'value' => '你好呀~',
                'label' => '侧边栏描述文本',
                'description' => '侧边栏描述文本'
            ],
            [
                'type' => 'Text',
                'name' => 'friends',
                'value' => '#',
                'label' => '好友列表',
                'description' => '好友列表链接'
            ],
            [
                'type' => 'Text',
                'name' => 'aboutLink',
                'value' => '#',
                'label' => '关于页面',
                'description' => '关于页面链接'
            ],
            [
                'type' => 'Text',
                'name' => 'loadingImg',
                'value' => get_assets('Emoji/bilibili/10002.png', false, false),
                'label' => '加载背景图',
                'description' => '设置图像加载的占位图'
            ]
        ]
    ],
    'Color-Options' => [
        'title' => '颜色设置',
        'fields' => [
            [
                'type' => 'ColorPicker',
                'name' => 'theme_color',
                'value' => '',
                'label' => '主题颜色',
                'description' => '选择网站的主题颜色，支持十六进制颜色值输入。'
            ],
            [
                'type' => 'ColorPicker',
                'name' => 'neutral_color',
                'value' => '',
                'label' => '中性色',
                'description' => '设置站点的中性色（Neutral）网站背景颜色、卡片颜色等。'
            ],
            [
                'type' => 'ColorPicker',
                'name' => 'secondary_color',
                'value' => '',
                'label' => '次要颜色',
                'description' => '设置站点的次要颜色（Secondary）。'
            ],
            [
                'type' => 'ColorPicker',
                'name' => 'accent_color',
                'value' => '',
                'label' => '强调颜色',
                'description' => '设置站点的强调颜色（Accent）。'
            ],

            [
                'type' => 'ColorPicker',
                'name' => 'info_color',
                'value' => '',
                'label' => '信息颜色',
                'description' => '设置站点的信息颜色（Info）。'
            ],
            [
                'type' => 'ColorPicker',
                'name' => 'success_color',
                'value' => '',
                'label' => '成功颜色',
                'description' => '设置站点的成功颜色（Success）。'
            ],
            [
                'type' => 'ColorPicker',
                'name' => 'warning_color',
                'value' => '',
                'label' => '警告颜色',
                'description' => '设置站点的警告颜色（Warning）。'
            ],
            [
                'type' => 'ColorPicker',
                'name' => 'error_color',
                'value' => '',
                'label' => '错误颜色',
                'description' => '设置站点的错误颜色（Error）。'
            ],
            [
                'type' => 'Radio',
                'name' => 'color_intensity',
                'value' => 'medium',
                'label' => '主题色调强度',
                'description' => '控制浅色/深色底色的浓淡程度',
                'layout' => 'vertical',
                'options' => [
                    'soft' => '更淡',
                    'medium' => '适中',
                    'bold' => '更浓'
                ]
            ],
            [
                'type' => 'Radio',
                'name' => 'border_radius',
                'value' => '0.5rem',
                'label' => '全局圆角',
                'description' => '选择全局圆角大小（影响卡片、输入框等）',
                'layout' => 'vertical',
                'options' => [
                    '0rem' => '0rem',
                    '0.25rem' => '0.25rem',
                    '0.5rem' => '0.5rem',
                    '1rem' => '1rem',
                    '2rem' => '2rem'
                ]
            ],
        ]
    ],
    'Post-Options' => [
        'title' => '文章设置',
        'fields' => [
            [
                'type' => 'Radio',
                'name' => 'enablePostInfo',
                'value' => '1',
                'label' => '显示文章信息框',
                'description' => '是否在文章底部显示版权、作者等信息框',
                'layout' => 'horizontal',
                'options' => [
                    '1' => '开启',
                    '0' => '关闭'
                ]
            ],
            [
                'type' => 'Radio',
                'name' => 'enableCCLogo',
                'value' => '1',
                'label' => '显示CC许可协议logo',
                'description' => '是否在文章底部显示CC许可协议logo',
                'layout' => 'horizontal',
                'options' => [
                    '1' => '开启',
                    '0' => '关闭'
                ]
            ],
            [
                'type' => 'Text',
                'name' => 'licenseName',
                'value' => 'CC BY-NC-SA 4.0',
                'label' => '许可协议名称',
                'description' => '文章信息框中显示的许可协议名称'
            ],
            [
                'type' => 'Text',
                'name' => 'licenseLink',
                'value' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/',
                'label' => '许可协议链接',
                'description' => '许可协议的跳转链接'
            ]
        ]
    ],
    'Other-Options' => [
        'title' => '其他设置',
        'fields' => [
            [
                'type' => 'Text',
                'name' => 'MetingApiUrl',
                'value' => '#',
                'label' => 'MetingApiUrl',
                'description' => '设置MetingApiUrl'
            ],
            [
                'type' => 'Textarea',
                'name' => 'pjax_Content',
                'value' => '',
                'label' => 'PJAX回调函数',
                'description' => 'PJAX回调函数，用于在PJAX加载完成后执行'
            ],
        ]
    ],
    'AI-Options' => [
        'title' => 'AI 设置',
        'fields' => [
            [
                'type' => 'Text',
                'name' => 'ai_show_badge',
                'value' => 'AI',
                'label' => '显示在摘要前面的徽章文本',
                'description' => '显示在摘要前面的徽章文本'
            ],
            [
                'type' => 'Text',
                'name' => 'ai_api_endpoint',
                'value' => 'https://api.openai.com/v1/chat/completions',
                'label' => 'API Endpoint',
                'description' => 'OpenAI 兼容的 API 地址'
            ],
            [
                'type' => 'Text',
                'name' => 'ai_api_key',
                'value' => '',
                'label' => 'API Key',
                'description' => 'API 密钥'
            ],
            [
                'type' => 'Text',
                'name' => 'ai_model',
                'value' => 'gpt-3.5-turbo',
                'label' => '模型名称',
                'description' => '使用的 AI 模型 (如 gpt-3.5-turbo, gpt-4)'
            ],
            [
                'type' => 'Textarea',
                'name' => 'ai_prompt_template',
                'value' => '请为以下文章生成一个简短的摘要（200字以内）：\n\n标题：${title}\n\n内容：\n${content}',
                'label' => '提示词模板',
                'description' => '支持变量：${title} (标题), ${content} (内容)'
            ]
        ]
    ]
];
