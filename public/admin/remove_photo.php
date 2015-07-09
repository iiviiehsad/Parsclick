<?php require_once("../../includes/initialize.php");
$session->confirm_author_logged_in();
if(empty($_GET["id"])) {
	$session->message("شناسه عکس شما پیدا نشد!");
	redirect_to("author_edit_profile.php");
}
$author = Author::find_by_id($_GET["id"]);
$result = $author->remove_photo();
if($result) {
	$session->message("عکس پروفایل شما با موفقیت حذف شد.");
	redirect_to("author_edit_profile.php");
} else { // Failure
	$session->message("عکس پروفایل شما حذف نشد!");
	redirect_to("author_edit_profile.php");
}
if(isset($database)) {
	$database->close_connection();
}
?>