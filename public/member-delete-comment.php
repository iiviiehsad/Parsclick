<?php
require_once("../includes/initialize.php");
$session->confirm_logged_in();
if(empty($_GET["id"])) {
	$session->message("شناسه نظر پیدا نشد!");
	redirect_to("member-courses");
}
$comment = Comment::find_by_id($_GET["id"]);
$course = Course::find_by_id($comment->course_id);
if($comment && $comment->delete()) {
	$session->message("نظر حذف شد.");
	redirect_to("member-comments?category={$course->category_id}&course={$course->id}");
} else {
	$session->message("نظر حذف نشد!");
	redirect_to("member-comments?category={$course->category_id}&course={$course->id}");
}
if(isset($database)) { $database->close_connection(); }