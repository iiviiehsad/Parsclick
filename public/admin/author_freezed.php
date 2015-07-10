<?php require_once("../../includes/initialize.php"); ?>
<?php $session->confirm_author_logged_in(); ?>
<?php $author = Author::find_by_id($session->id);
if($author->status == 2) {
	redirect_to("deactivated.php");
}
$errors = "";
if(isset($_POST["resend_email"])) {
	$author->create_reset_token($author->username);
	$result = $author->email_confirmation_details($author->username);
	if($result) {
		$session->message("ایمیل برای فعال کردن عضویت نویسندگس دوباره فرستاده شد. لطفا از اینجا خارج شوید و ایمیل خود را چک کنید.");
		redirect_to("author_freezed.php");
	} else {
		$errors = "نتوانستیم ایمیل بفرستیم! لطفا بعدا سعی کنید یا با مدیر سایت تماس بگیرید.";
	}
} else {
}
?>
<?php include_layout_template("admin_header.php"); ?>
	<style type="text/css">
		.jumbotron {
			padding     : 50px;
			margin      : 0.1%;
			font-size   : 24px;
			font-weight : 200;
			line-height : 2.14285714;
			color       : inherit;
			border      : 3px solid #475C98;
		}
	</style>
	<header class="clearfix">
		<section id="branding">
			<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Parsclick"></a>
		</section>
	</header>
<?php echo output_message($message, $errors); ?>
	<div class="jumbotron">
		<h1>با عرض پوزش <?php echo ucwords(strtolower($author->full_name())); ?>!</h1>
		<p>حق اشتراک شما به یکی از دلایل زیر کار نمیکند:</p>
		<ul class="text-warning">
			<li>شما لینک فعال کردن عضویت نویسندگی خود که با ایمیل دریافت کردید کلیک نکردید.</li>
			<li>لینک به ایمیل شما فرستاده شده اما شما ایمیل را پیدا نکردید.</li>
			<li>شما اسپم ایمیل خود را برای دریافت ایمیل چک نکردید.</li>
		</ul>
		<form action="author_freezed.php" method="POST">
			<p>
				<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
				<button type="submit" class="btn btn-success btn-large" name="resend_email" role="button">
					دوباره ایمیل رابفرست
				</button>
			</p>
		</form>
	</div>
<?php include_layout_template("admin_footer.php"); ?>