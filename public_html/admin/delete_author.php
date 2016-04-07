<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
if(empty($_GET['id'])) {
	$session->message('شناسه نویسنده پیدا نشد.');
	redirect_to('author_list.php');
}
$author = Author::find_by_id($_GET['id']);
$result = $author->delete();
if($result) {
	$session->message("نویسنده {$author->full_name()} حذف شد!");
	redirect_to('author_list.php');
} else { // Failure
	$session->message('نویسنده حذف نشد!');
	redirect_to('author_list.php');
}
if(isset($database)) $database->close_connection();