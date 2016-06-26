<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
if (empty($_GET['id'])) {
	$session->message('شناسه موضوع پیدا نشد!');
	redirect_to('admin_notifications.php');
}
$notifications = Notification::find_by_id($_GET['id'], FALSE);
if ($notifications->delete()) {
	$session->message('موضوع حذف شد!');
	redirect_to('admin_notifications.php');
} else {
	$session->message('موضوع حذف نشد!');
	redirect_to('admin_notifications.php');
}
if (isset($database)) $database->close_connection();