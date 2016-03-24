<?php
require_once("../../includes/initialize.php");
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
if( ! $author) {
	$session->message("شناسه کاربری پیدا نشد!");
	redirect_to("author_edit_profile.php");
}
$result = $author->remove_photo();
if($result) {
	$session->message("عکس پروفایل شما حذف شد");
	redirect_to("author_edit_profile.php");
} else { // Failure
	$session->message("حذف عکس موفقیت آمیز نبود.");
	redirect_to("author_edit_profile.php");
}
if(isset($database)) $database->close_connection();