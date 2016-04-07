w<?php
require_once('../includes/initialize.php');
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$course_id = $_POST['course'];
if(empty($course_id)) {
	$session->message('شناسه درس پیدا نشد!');
	redirect_to('member-courses');
}
$playlist            = new Playlist();
$playlist->id        = (int) '';
$playlist->member_id = (int) $member->id;
$playlist->course_id = (int) $course_id;
$result              = $playlist->create();
if($result) {
	$session->message('درس موفقیت آمیز به لیست پخش اضافه شد.');
	redirect_to($_SERVER['HTTP_REFERER']);
} else {
	$session->message('اضافه درس به لیست پخش موفقیت آمیز نبود!');
	redirect_to($_SERVER['HTTP_REFERER']);
}
if(isset($database)) $database->close_connection();