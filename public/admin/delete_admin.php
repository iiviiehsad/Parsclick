<?php require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
if(empty($_GET["id"])) {
	$session->message("شناسه مدیر پیدا نشد!");
	redirect_to("admin_list.php");
}
$admin = Admin::find_by_id($_GET["id"]);
if($session->id == $admin->id) {
	$session->message("شما قادر به حذف خود نیستید!");
	redirect_to("admin_list.php");
} else {
	$result = $admin->delete();
	if($result) {
		$session->message("مدیر {$admin->full_name()} حذف شد.");
		redirect_to("admin_list.php");
	} else { // Failure
		$session->message("مدیر حذف نشد!");
		redirect_to("admin_list.php");
	}
}
if(isset($database)) {
	$database->close_connection();
}
?>
