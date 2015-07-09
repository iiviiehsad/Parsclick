<?php require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
if(empty($_GET["id"])) {
	$session->message("شناسه عضویت پیدا نشد!");
	redirect_to("member_list.php");
}
$member = Member::find_by_id($_GET["id"]);
$result = $member->delete();
if($result) {
	$session->message("عضو {$member->full_name()} حذف شد.");
	redirect_to("member_list.php");
} else { // Failure
	$session->message("عضو حذف نشد!");
	redirect_to("member_list.php");
}
if(isset($database)) {
	$database->close_connection();
}
?>
