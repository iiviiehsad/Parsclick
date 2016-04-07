<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
if(empty($_GET['subject'])) {
	$session->message('شناسه موضوع پیدا نشد!');
	redirect_to('admin_articles.php');
}
$subject   = Subject::find_by_id($_GET['subject'], FALSE);
$pages_set = Article::num_articles_for_subject($subject->id, FALSE);
if($pages_set > 0) { // if there is any article for the subject
	$session->message('قادر به حذف موضوع با مقالات نیستیم!');
	redirect_to("admin_articles.php?subject={$subject->id}");
}
$result = $subject->delete();
if($result) { // Success
	$session->message("موضوع {$subject->name} حذف شد!");
	redirect_to('admin_articles.php');
} else { // Failure
	$session->message('موضوع حذف نشد!');
	redirect_to("admin_articles.php?subject={$subject->id}");
}
if(isset($database)) $database->close_connection();