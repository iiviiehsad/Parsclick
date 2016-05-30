<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
if (empty($_GET['subject'])) {
	$session->message('شناسه موضوع پیدا نشد!');
	redirect_to('admin_articles.php');
}
$subject   = Subject::find_by_id($_GET['subject'], FALSE);
$pages_set = Article::num_articles_for_subject($subject->id, FALSE);
if ($pages_set > 0) { 
	$session->message('قادر به حذف موضوع با مقالات نیستیم!');
	redirect_to("admin_articles.php?subject={$subject->id}");
}
if ($subject->delete()) { 
	$session->message("موضوع {$subject->name} حذف شد!");
	redirect_to('admin_articles.php');
} else { 
	$session->message('موضوع حذف نشد!');
	redirect_to("admin_articles.php?subject={$subject->id}");
}
if (isset($database)) $database->close_connection();