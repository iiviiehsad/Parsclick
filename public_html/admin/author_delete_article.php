<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
if (empty($_GET['article'])) {
	$session->message('شناسه مقاله پیدا نشد!');
	redirect_to('author_articles.php');
}
$article = Article::find_by_id($_GET['article'], FALSE);
$subject = Subject::find_by_id($_GET['subject'], FALSE);
$result  = $article->delete();
if ($result) {
	$session->message("مقاله {$article->name} حذف شد.");
	redirect_to("author_articles.php?subject={$subject->id}");
} else {
	$session->message('مقاله حذف نشد!');
	redirect_to("edit_articles.php?subject={$subject->id}");
}
if (isset($database)) $database->close_connection();