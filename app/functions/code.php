<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once __DIR__ . '/shortcode.lib.php';
require_once __DIR__ . '/ai.php';
require_once __DIR__ . '/shortcode.php';

// Register AI Hooks
// Removed automatic generation as per request. Now using manual button.

/**
 * OKOME 主题功能类
 * 提供主题相关的功能方法
 */
class OKOME
{
    /**
     * 处理评论中的表情符号
     * 将评论中的表情触发词替换为对应的图片
     * 
     * @param string $commentStr 评论内容
     * @return string 处理后的评论内容
     */
    public static function processCommentEmoji($commentStr)
    {
        if (empty($commentStr)) {
            return $commentStr;
        }

        $EmojiJson = __DIR__ . '/../../assets/owo.json';
        $json = @file_get_contents($EmojiJson);
        if ($json === false) {
            return $commentStr;
        }

        $data = json_decode($json, true);
        if (!is_array($data)) {
            return $commentStr;
        }

        foreach ($data as $category) {
            if (!is_array($category)) {
                continue;
            }

            if (isset($category['type']) && $category['type'] === 'image' && isset($category['container']) && is_array($category['container'])) {
                foreach ($category['container'] as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $trigger = isset($item['input']) ? (string)$item['input'] : '';
                    $icon = isset($item['icon']) ? (string)$item['icon'] : '';
                    if ($trigger === '' || $icon === '') {
                        continue;
                    }

                    $alt = isset($item['text']) ? (string)$item['text'] : '';
                    $img = '<img src="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '" class="owo-emoji">';
                    $commentStr = str_replace($trigger, $img, $commentStr);
                }
            }
        }

        return $commentStr;
    }
}
