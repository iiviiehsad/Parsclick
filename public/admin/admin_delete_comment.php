<?php require_once("../../includes/initialize.php"); ?>
<?php $session->confirm_admin_logged_in(); ?>
<?php
$comment_id = $_GET["id"];
if(empty($comment_id)) {
	$session->message("No comment ID was provided!");
	redirect_to("admin_courses.php");
}
$comment = Comment::find_by_id($comment_id);
if($comment && $comment->delete()) {
	$session->message("The comment was deleted.");
	redirect_to("admin_comments.php?course={$comment->course_id}");
} else {
	$session->message("The comment could not be deleted.");
	redirect_to("admin_comments.php?course={$comment->course_id}");
}
if(isset($database)) { $database->close_connection(); }