<?php require_once("../includes/initialize.php");
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$member_id = (int)$member->id;
$body      = trim($_POST["body"]);
$course_id = trim($_POST["course"]);
Comment::make($member_id, $course_id, $body)->create();
if(isset($database)) $database->close_connection();