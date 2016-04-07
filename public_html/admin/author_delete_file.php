<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
if(empty($_GET['id'])) {
	$session->message('هیچ فایلی پیدا نشد!');
	redirect_to($_SERVER['HTTP_REFERER']);
}
$file = File::find_by_id($_GET['id']);
if($file && $file->destroy()) {
	$session->message("فایل {$file->description} حذف شد.");
	redirect_to($_SERVER['HTTP_REFERER']);
} else {
	$session->message("فایل {$file->description} حذف نشد!");
	redirect_to($_SERVER['HTTP_REFERER']);
}
if(isset($database)) $database->close_connection();