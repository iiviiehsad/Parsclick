<?php require_once("../includes/initialize.php");
$token = $_GET['token'];
// Confirm that the token sent is valid
$user = Member::find_by_token($token);
if(!$user || !$token) {
	// Token wasn't sent or didn't match a user.
	redirect_to('login.php');
}
$user->status = 1;
$result       = $user->update();
if($result) {
	$user->delete_reset_token($user->username);
	$session->message("متشکریم! ایمیل شما تایید شد. شما الآن وارد شدید.");
	$session->login($user);
	redirect_to("member.php");
} else {
	$session->message("متاسفانه نتوانستیم ایمیل شما را تایید کنیم! لطفا وارد سیستم شوید و روی دگمه دوباره ایمیل بفرست کلیک کنید.");
	redirect_to('login.php');
}
?>