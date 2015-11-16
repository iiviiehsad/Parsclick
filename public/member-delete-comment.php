<?php
require_once("../includes/initialize.php");
$session->confirm_logged_in();
if(empty($_GET["id"])) {
	$session->message("No comment ID was provided.");
	redirect_to("member-courses");
}

$comment = Comment::find_by_id($_GET["id"]);
if($comment && $comment->delete()) {
	$session->message("نظر حذف شد.");
	redirect_to("member-comments?course={$comment->course_id}");
} else {
	$session->message("نظر حذف نشد!");
	redirect_to("member-comments?course={$comment->course_id}");
}
if(isset($database)) {
	$database->close_connection();
}
?>