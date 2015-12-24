<?php require_once("../includes/initialize.php");
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$member_id = (int)$member->id;
$body      = trim($_POST["body"]);
$article   = trim($_POST["article"]);
ArticleComment::make($member_id, $article, $body)->create();
if(isset($database)) $database->close_connection();