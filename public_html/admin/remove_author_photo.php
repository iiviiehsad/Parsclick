<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
if( ! $_GET['id']) {
	redirect_to('edit_author.php');
}
$author = Author::find_by_id($_GET['id']);
if( ! $author) {
	$session->message('شناسه کاربری پیدا نشد!');
	redirect_to("edit_author.php?id={$author->id}");
}
$result = $author->remove_photo();
if($result) {
	$session->message('عکس پروفایل حذف شد.');
	redirect_to("edit_author.php?id={$author->id}");
} else { // Failure
	$session->message('حذف عکس موفقیت آمیز نبود.');
	redirect_to("edit_author.php?id={$author->id}");
}
if(isset($database)) $database->close_connection();