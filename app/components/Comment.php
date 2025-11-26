<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php
function TTDF_get_avatar_src($comments, $size = 64)
{
    $mail = '';
    if (isset($comments->mail)) {
        $mail = $comments->mail;
    }
    $hash = md5(strtolower(trim((string)$mail)));
    if ($hash) {
        return 'https://cravatar.cn/avatar/' . $hash . '?s=' . intval($size) . '&r=G';
    }
    return 'https://img.daisyui.com/images/stock/photo-1567653418876-5bb0e566e1c2.webp';
}

function threadedComments($comments, $options)
{
?>
    <?php $level = isset($comments->levels) ? $comments->levels : (isset($comments->_levels) ? $comments->_levels : 0); ?>
    <div id="<?php $comments->theId(); ?>">
        <div class="flex flex-row <?php echo ($level > 0) ? 'gap-4' : 'gap-4' ?> items-start mb-2">
            <img class="mask mask-squircle sm:w-16 w-10" src="<?php echo TTDF_get_avatar_src($comments, 64); ?>" />
            <div class="card <?php echo ($level > 0) ? 'bg-base-100' : 'bg-base-100 shadow-sm'; ?> <?php echo ($level > 1) ? 'p-4 sm:p-8' : 'p-4 sm:p-8'; ?> flex-1 group" tabindex="0">
                <div class="comment_data">
                    <strong><?php $comments->author(); ?></strong>
                </div>
                <div class="comment_body"><?php $comments->content(); ?></div>
                <p class="text-sm text-gray-500"><?php GetComment::FormatDate(); ?> <span class="comment-reply hidden group-hover:inline-block group-focus-within:inline-block"><?php $comments->reply('回复'); ?></span></p>
            </div>
        </div>
        <?php if ($comments->children): ?>
            <ol class="mt-4">
                <?php $comments->threadedComments($options); ?>
            </ol>
        <?php endif; ?>
    </div>
<?php
}
?>
<main>
    <div class="mx-auto w-full max-w-full sm:max-w-5xl sm:px-4 flex flex-col  ">
        <div class="card bg-base-transparent pl-4 pr-4 md:pl-4 md:pr-4">
            <article class="prose max-w-none">
                <div class="flex flex-row items-baseline gap-2">
                    <h2 class="text-2xl font-bold mb-2">评论</h2>
                    <p class="text-sm text-gray-500"><?php $this->commentsNum('没有评论', '1条评论', '%d条评论'); ?></p>
                </div>

                <?php $this->comments()->to($comments); ?>
                <div id="<?php $this->respondId(); ?>" class="mb-8">
                    <div id="comments-form" class="card bg-base-100 p-4 mb-8 md:p-8 shadow-sm">
                        <h3>新的评论</h3>
                        <form class="transition-form" data-swup-form method="post" action="<?php $this->commentUrl() ?>" id="comment_form">

                            <!-- 如果当前用户已经登录 -->
                            <?php if (GetUser::Login(false)): ?>
                                <!-- 显示当前登录用户的用户名以及登出连接 -->
                                <?php $this->user->screenName(); ?>已登录
                                <!-- 若当前用户未登录 -->
                            <?php else: ?>
                                <!-- 要求输入名字、邮箱、网址 -->
                                <div class="grid grid-cols-3  gap-4 comments-Input items-center w-full">
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">昵称</legend>
                                        <input type="text" name="author" class="input text input-neutral" size="35" value="<?php $this->remember('author'); ?>" placeholder="昵称*" />
                                    </fieldset>
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">邮箱</legend>
                                        <input type="text" name="mail" class="input text input-neutral" size="35" value="<?php $this->remember('mail'); ?>" placeholder="邮箱*" />
                                    </fieldset>
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">博客链接</legend>
                                        <input type="text" name="url" class="input text input-neutral" size="35" value="<?php $this->remember('url'); ?>" placeholder="博客链接" />
                                    </fieldset>
                                    <input type="hidden" name="receiveMail" id="receiveMail" value="yes" />
                                </div>
                            <?php endif; ?>
                            <input name="_" type="hidden" id="comment_" value="<?php echo Helper::security()->getToken(str_replace(array('?_pjax=%23wrap', '?_pjax=%23pjax-load', '&_pjax=%23wrap'), '', $this->request->getUrlPrefix() . $this->request->getRequestUri())); ?>" />

                            <div id="comments-textarea-wrap" class="w-full mt-4">
                                <textarea id="comments-textarea" name="text" placeholder="内容" class="textarea-neutral textarea w-full"><?php $this->remember('text'); ?></textarea>

                            </div>
                            <div class="flex flex-row gap-2 card mt-4 items-center justify-start">
                                <input type="submit" value="发送" class="submit btn  btn-primary w-auto" id="comment-submit" />
                                <?php if ($comments->cancelReply() != ""): ?>
                                    <span class="cancel-comment-reply text-sm text-gray-500 btn btn-soft"><?php $comments->cancelReply(); ?></span>
                                <?php endif; ?>
                            </div>

                        </form>
                    </div>
                </div>
                <?php if ($comments->have()): ?>

                    <?php $comments->listComments(array('before' => '<div id="comment_list" >', 'after' => '</div>')); ?>
                <?php endif; ?>


            </article>
        </div>
    </div>
</main>