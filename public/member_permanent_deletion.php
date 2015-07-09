<?php require_once("../includes/initialize.php");
$session->confirm_logged_in();
if(empty($_GET["id"])) {
	$session->message("شناسه کاربری پیدا نشد!");
	redirect_to("reactivate.php");
}
$member = Member::find_by_id($_GET["id"]);
$result = $member->delete();
if($result) {
	$session->logout();
	redirect_to("login.php");
} else {
	$session->message("حذف کاربر موفقیت آمیز نبود!");
	redirect_to("member_profile.php");
}
if(isset($database)) {
	$database->close_connection();
}
?>
