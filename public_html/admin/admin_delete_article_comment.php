<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$comment_id = $_GET['id'];
if (empty($comment_id)) {
	$session->message('شناسه نظر پیدا نشد!');
	redirect_to('admin_articles.php');
}
$comment = ArticleComment::find_by_id($comment_id);
if ($comment && $comment->delete()) {
	$session->message('نظر حذف شد.');
	redirect_to($_SERVER['HTTP_REFERER'] . '#comments');
} else {
	$session->message('نظر حذف نشد!');
	redirect_to($_SERVER['HTTP_REFERER'] . '#comments');
}
if (isset($database)) $database->close_connection();