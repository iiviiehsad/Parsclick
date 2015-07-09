<?php require_once("../includes/initialize.php");
$session->confirm_logged_in();
if(empty($_GET["id"])) {
	$session->message("No member ID was provided.");
	redirect_to("member_edit_profile.php");
}
$member = Member::find_by_id($_GET["id"]);
$result = $member->remove_photo();
if($result) {
	$session->message("عکس پروفایل شما با موفقیت آمیز خذف شد.");
	redirect_to("member_edit_profile.php");
} else { // Failure
	$session->message("حذف عکس موفقیت آمیز نبود.");
	redirect_to("member_edit_profile.php");
}
if(isset($database)) {
	$database->close_connection();
}
?>