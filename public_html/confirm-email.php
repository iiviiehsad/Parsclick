<?php require_once('../includes/initialize.php');
$token = $_GET['token'];
$user  = Member::find_by_token($token);
if ( ! $user || ! $token) {
	$session->message('شناسه یا توکن معتبر نیست!');
	redirect_to('login');
}
$user->status = 1;
if ($user->update()) {
	$user->delete_reset_token($user->username);
	$session->message('متشکریم! ایمیل شما تایید شد. شما الآن وارد شدید.');
	$session->login($user);
	redirect_to('member');
} else {
	$session->message('متاسفانه نتوانستیم ایمیل شما را تایید کنیم! لطفا وارد سیستم شوید و روی دگمه دوباره ایمیل بفرست کلیک کنید.');
	redirect_to('login');
}