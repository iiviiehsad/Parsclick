<?php
require_once("../includes/initialize.php");
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
if($member->status == 2) {
	redirect_to("blocked");
}
$errors = "";
if(isset($_POST["resend_email"])) {
	if(request_is_post() && $session->request_is_same_domain()) {
		if($session->csrf_token_is_valid() && $session->csrf_token_is_recent()) {
			$member->create_reset_token($member->username);
			$result = $member->email_confirmation_details($member->username);
			if($result) {
				$session->message("ایمیل برای فعال کردن عضویت دوباره فرستاده شد. لطفا ایمیل خود را چک کنید و از اینجا خارج شوید.");
				redirect_to("freezed.php");
			} else {
				$errors = "نتوانستیم ایمیل بفرستیم! لطفا بعدا سعی کنید یا با مدیر سایت تماس بگیرید.";
			}
		} else {
			$errors = "شناسه CSRF معتبر نیست!";
		}
	} else {
		$errors = "درخواست معتبر نیست!";
	}
} elseif(isset($_POST["update_email"])) {
	if(request_is_post() && $session->request_is_same_domain()) {
		if($session->csrf_token_is_valid() && $session->csrf_token_is_recent()) {
			$member->email = trim($_POST["email"]);
			$result        = $member->save();
			if($result) {
				$session->message("شما ایمیل خود را بروز رساندید. حالا روی دوباره ایمیل را بفرست کلیک کنید.");
				redirect_to("freezed");
			} else {
				$errors = "بروزرسانی ایمیل موفقیت آمیز نبود!";
			}
		} else {
			$errors = "شناسه CSRF معتبر نیست!";
		}
	} else {
		$errors = "درخواست معتبر نیست!";
	}
} else {
}
?>
<?php include("../includes/layouts/header.php"); ?>
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
			<a href="/"><img src="images/misc/logo.png" alt="Logo for Parsclick"></a>
		</section>
	</header>
<?php echo output_message($message, $errors); ?>
	<div class="jumbotron">
		<h1>با عرض پوزش <?php echo ucwords(strtolower($member->full_name())); ?>!</h1>
		<p>حق اشتراک شما به یکی از دلایل زیر کار نمیکند:</p>
		<ul class="text-warning">
			<li>شما لینک فعال کردن عضویت خود که با ایمیل دریافت کردید کلیک نکردید.</li>
			<li>سیستم در حال چک کردن دوباره ی ایمیل شماست.</li>
		</ul>
		<form action="freezed" method="POST">
			<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
			<?php echo $session->csrf_token_tag(); ?>
			<button type="submit" class="btn btn-success btn-large" name="resend_email" role="button">
				دوباره ایمیل رابفرست
			</button>
		</form>
		<ul class="text-warning">
			<li>فقط اگر ایمیل شما اون چیزی نیست که قبلا وارد کردید، ایمیل جدید خود را وارد کنید روی تغییر بده کلیک کنید و بعد
			    روی  دوباره بفرست کلیک کنید، وگرنه تنها روی دوباره بفرست کلیک کنید.
			</li>
		</ul>
		<form action="freezed" method="POST">
			<div class="input-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
				<?php echo $session->csrf_token_tag(); ?>
				<input onblur="checkEmail();" onkeyup="checkEmail();" class="edit col-xs-12 col-sm-8 col-md-8 col-lg-8 input-small" type="email" name="email" id="email" placeholder="Email" required value="<?php echo htmlentities($member->email); ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$"/>
					<span class="input-group-btn">
						<button class="btn btn-primary btn-small" type="submit" name="update_email">
							تغییر بده
						</button>
					</span>
			</div>
		</form>
		<small><span class="pull-left wow flash infinite" data-wow-duration="2s" id="confirmMessage"></span></small>
		<br/>
	</div>
<?php include("../includes/layouts/footer.php"); ?>