<?php require_once("../../includes/initialize.php"); ?>
<?php $session->confirm_admin_logged_in(); ?>
<?php
if(empty($_GET['id'])) {
	$session->message("شناسه فایل پیدا نشد!");
	redirect_to($_SERVER["HTTP_REFERER"]);
}
$file = File::find_by_id($_GET['id']);
if($file && $file->destroy()) {
	$session->message("فایل {$file->description} حذف شد.");
	redirect_to($_SERVER["HTTP_REFERER"]);
} else {
	$session->message("فایل {$file->description} حذف نشد!");
	redirect_to($_SERVER["HTTP_REFERER"]);
}
?>
<?php if(isset($database)) {
	$database->close_connection();
} ?>