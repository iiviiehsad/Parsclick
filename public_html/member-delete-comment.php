<?php require_once('../includes/initialize.php');
$session->confirm_logged_in();
if (empty($_GET['id'])) {
	$session->message('شناسه دیدگاه پیدا نشد!');
	redirect_to('member-courses');
}
$comment = Comment::find_by_id($_GET['id']);
$course  = Course::find_by_id($comment->course_id);
if ( ! $comment || ! $course) {
	$session->message('درس یا نظر موجود نیست!');
	redirect_to('member-courses');
}
if ($comment->member_id == $session->id) {
	if ($comment->delete()) {
		$session->message('دیدگاه حذف شد.');
		redirect_to($_SERVER['HTTP_REFERER']);
	} else {
		$session->message('دیدگاه حذف نشد!');
		redirect_to($_SERVER['HTTP_REFERER']);
	}
}
$session->message('دیدگاه متعلق به شما نیست!');
redirect_to($_SERVER['HTTP_REFERER']);
if (isset($database)) $database->close_connection();