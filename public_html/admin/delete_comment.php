<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
if (empty($_GET['id'])) {
	$session->message('شناسه نظر پیدا نشد!');
	redirect_to('article-comments.php');
}
$comment = Comment::find_by_id($_GET['id']);
if ($comment && $comment->delete()) {
	$session->message('نظر حذف شد.');
	redirect_to("article-comments.php?id={$comment->course_id}");
} else {
	$session->message('نظر حذف نشد!');
	redirect_to('list_comments.php');
}
if (isset($database)) $database->close_connection();