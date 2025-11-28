<?php

/**
 * GetComment 方法
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class GetComment
{
    use ErrorHandler, SingletonWidget;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    // 获取评论
    public static function Comments()
    {
        try {
            echo self::getArchive()->comments;
        } catch (Exception $e) {
            self::handleError('获取评论失败', $e);
        }
    }



    /**
     * 语义化评论日期
     * 
     * 
     * 
     */
    public static function FormatDate()
    {
        try {
            $comments = Widget_Comments_Archive::widget('Widget_Comments_Archive');
            $timestamp = (int)$comments->created;
            $diff = time() - $timestamp;
            $abs = abs($diff);
            if ($abs < 60)
                $data = $abs . "秒" . ($diff >= 0 ? "前" : "后");
            else if ($abs < 3600)
                $data = (int)($abs / 60) . "分钟" . ($diff >= 0 ? "前" : "后");
            else if ($abs < 86400)
                $data = (int)($abs / 3600) . "小时" . ($diff >= 0 ? "前" : "后");
            else
                $data = (int)($abs / 86400) . "天" . ($diff >= 0 ? "前" : "后");
            echo $data;
        } catch (Exception $e) {
            echo '获取日期失败';
        }
    }

    // 获取评论页面
    public static function CommentsPage()
    {
        try {
            echo self::getArchive()->commentsPage;
        } catch (Exception $e) {
            self::handleError('获取评论页面失败', $e);
        }
    }

    // 获取评论列表
    public static function CommentsList()
    {
        try {
            echo self::getArchive()->commentsList;
        } catch (Exception $e) {
            self::handleError('获取评论列表失败', $e);
        }
    }

    // 获取评论数
    public static function CommentsNum()
    {
        try {
            echo self::getArchive()->commentsNum;
        } catch (Exception $e) {
            self::handleError('获取评论数失败', $e);
        }
    }

    // 获取评论id
    public static function RespondId()
    {
        try {
            echo self::getArchive()->respondId;
        } catch (Exception $e) {
            self::handleError('获取评论id失败', $e);
        }
    }

    // 取消回复
    public static function CancelReply()
    {
        try {
            echo self::getArchive()->cancelReply();
        } catch (Exception $e) {
            self::handleError('取消回复失败', $e);
        }
    }

    // Remember
    public static function Remember($field)
    {
        try {
            echo self::getArchive()->remember($field);
        } catch (Exception $e) {
            self::handleError('获取Remember失败', $e);
        }
    }

    // 获取评论表单
    public static function CommentsForm()
    {
        try {
            echo self::getArchive()->commentsForm;
        } catch (Exception $e) {
            self::handleError('获取评论表单失败', $e);
        }
    }

    // 获取分页
    public static function PageNav($prev = '&laquo; 前一页', $next = '后一页 &raquo;')
    {
        try {
            // 使用评论专用的 Widget
            $comments = Widget_Comments_Archive::widget('Widget_Comments_Archive');
            $comments->pageNav($prev, $next);
        } catch (Exception $e) {
            self::handleError('评论分页导航失败', $e);
        }
    }
}
