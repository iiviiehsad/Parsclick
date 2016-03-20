<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
if(empty($_GET["article"])) {
	$session->message("شناسه مقاله پیدا نشد");
	redirect_to("admin_articles.php");
}
$article = Article::find_by_id($_GET["article"], FALSE);
$subject = Subject::find_by_id($_GET["subject"], FALSE);
$result  = $article->delete();
if($result) { // Success
	$session->message("مقاله {$article->name} حذف شد.");
	redirect_to("admin_articles.php?subject={$subject->id}");
} else { // Failure
	$session->message("مقاله حذف نشد!");
	redirect_to("edit_article.php?subject={$subject->id}");
}
if(isset($database)) {
	$database->close_connection();
}