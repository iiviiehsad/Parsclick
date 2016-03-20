<?php require_once("../includes/initialize.php");
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
if(isset($_POST["playlist"])) {
	$playlist_id = $_POST["playlist"];
}
if(isset($_GET["playlist"])) {
	$playlist_id = $_GET["playlist"];
}
if( ! $playlist_id) {
	$session->message("شناسه لیست پیدا نشد!");
	redirect_to($_SERVER["HTTP_REFERER"]);
}
$playlist = Playlist::find_by_id($playlist_id);
$result   = $playlist->delete();
if($result) {
	$session->message("درس با موفقیت آمیز از لیست پخش حذف شد.");
	redirect_to($_SERVER["HTTP_REFERER"]);
} else {
	$session->message("حذف درس از لیست پخش موفقیت آمیز نبود.");
	redirect_to($_SERVER["HTTP_REFERER"]);
}
if(isset($database)) {
	$database->close_connection();
}