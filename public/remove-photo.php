<?php
require_once("../includes/initialize.php");
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
if(!$member) {
	$session->message("شناسه کاربری پیدا نشد!");
	redirect_to("member-edit-profile");
}
$result = $member->remove_photo();
if($result) {
	$session->message("عکس پروفایل شما حذف شد");
	redirect_to("member-edit-profile");
} else { // Failure
	$session->message("حذف عکس موفقیت آمیز نبود.");
	redirect_to("member-edit-profile");
}
if(isset($database)) { $database->close_connection(); }