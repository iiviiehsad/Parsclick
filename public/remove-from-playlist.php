<?php require_once("../includes/initialize.php");
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
if(isset($_POST["course"])) {
	$course_id = $_POST["course"];
}
if(isset($_GET["course"])) {
	$course_id = $_GET["course"];
}
if(empty($course_id)) {
	$session->message("شناسه درس پیدا نشد!");
	redirect_to("member-courses");
}
$playlist = Playlist::find_by_id($course_id);
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
?>