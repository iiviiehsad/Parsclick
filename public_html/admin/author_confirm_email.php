<?php
require_once("../../includes/initialize.php");
$token = $_GET['token'];
$user  = Author::find_by_token($token);
if( ! $user || ! $token) {
	redirect_to('index.php');
}
$user->status = 1;
$result       = $user->update();
if($result) {
	$user->delete_reset_token($user->username);
	$session->message("متشکریم! ایمیل شما تایید شد. شما الآن وارد شدید. لطفا پسوردتان را عوض کنید.");
	$session->author_login($user);
	redirect_to("author.php");
} else {
	$session->message("متاسفانه نتوانستیم ایمیل شما را تایید کنیم! لطفا وارد سیستم شوید و روی دگمه دوباره ایمیل بفرست کلیک کنید.");
	redirect_to('index.php');
}