<?php require_once("../includes/initialize.php"); ?>
<?php $session->confirm_logged_in(); ?>
<?php $member = Member::find_by_id($session->id);
if($member->status == 2) {redirect_to("blocked.php");}
$errors       = "";
if(isset($_POST["resend_email"])) {
	$member->create_reset_token($member->username);
	$result = $member->email_confirmation_details($member->username);
	if($result) {
		$session->message("ایمیل برای فعال کردن عضویت دوباره فرستاده شد. لطفا ایمیل خود را چک کنید و از اینجا خارج شوید.");
		redirect_to("freezed.php");
	} else {
		$errors = "نتوانستیم ایمیل بفرستیم! لطفا بعدا سعی کنید یا با مدیر سایت تماس بگیرید.";
	}
} else {
}
?>
<?php include("../includes/layouts/header.php"); ?>
	<style type="text/css">
		.jumbotron {
			padding       : 50px;
			margin        : 0.1%;
			font-size     : 24px;
			font-weight   : 200;
			line-height   : 2.14285714;
			color         : inherit;
			border        : 3px solid #475C98;
		}
	</style>
	<header class="clearfix">
		<section id="branding">
			<a href="index.php"><img src="images/misc/logo.png" alt="Logo for Parsclick"></a>
		</section>
	</header>
<?php echo output_message($message, $errors); ?>
	<div class="jumbotron">
		<h1>با عرض پوزش <?php echo ucwords(strtolower($member->full_name())); ?>!</h1>
		<p>حق اشتراک شما به یکی از دلایل زیر کار نمیکند:</p>
		<ul class="text-warning">
			<li>شما لینک فعال کردن عضویت خود که با ایمیل دریافت کردید کلیک نکردید.</li>
			<li>لینک به ایمیل شما فرستاده شده اما شما ایمیل را پیدا نکردید.</li>
			<li>شما اسپم ایمیل خود را برای دریافت ایمیل چک نکردید.</li>
		</ul>
		<form action="freezed.php" method="POST">
			<p>
				<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
				<button type="submit" class="btn btn-success btn-large" name="resend_email" role="button">
					دوباره ایمیل رابفرست
				</button>
			</p>
		</form>
	</div>
<?php include("../includes/layouts/footer.php"); ?>