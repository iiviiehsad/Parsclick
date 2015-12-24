<?php
require_once("../includes/initialize.php");
$title    = "پارس کلیک - فراموشی پسورد
";
$filename = basename(__FILE__);
if(isset($_POST["submit"])) {
	$username = $_POST['username'];
	if(has_presence($username)) {
		// Search our fake database to retrieve the user data
		$user = Member::find_by_username($username);
		if($user) {
			// Username was found; okay to reset
			$user->create_reset_token($username);
			$email = $user->email_reset_token($username);
			if($email) {
				$message = "ایمیل فرستاده شد.";
			} else {
				$message = "خطا! ایمیل فرستاده نشد.";
			}
		} else {
			// Username was not found; don't do anything
		}
		// Message returned is the same whether the user
		// was found or not, so that we don't reveal which
		// user names exist and which do not.
		$message = "لینکی برای باز نشاندن پسورد به ایمیل آدرسی فرستاده شد که در دیتابیس موجود است.";
	} else {
		$message = "لطفا اسم کاربری را وارد کنید.";
	}
} else {
	$username = "";
}
?>
<?php include_layout_template("header.php"); ?>
<?php include "_/components/php/nav.php"; ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-key"></i> بازیافت پسورد </h2>
			<br/>
			<form class="form-horizontal" action="forgot" method="POST" accept-charset="utf-8">
				<fieldset>
					<legend>اسم کاربری شما چیست؟</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">
							اسم کاربری &nbsp;
						</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon arial"><span class="glyphicon glyphicon-user"></span></span>
								<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" autofocus placeholder="Username" value="<?php echo htmlentities($username); ?>" maxlength="30" required/>
							</div>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
						<div class="controls">
							<a href="login" class="btn btn-danger">لغو</a>
							<button class="btn btn-primary" name="submit" id="submit" type="submit">فرستادن</button>
						</div>
					</section>
					<section class="row">
						<a href="forgot-username" class="col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							اسم کاربری یادتون نیست؟
						</a>
					</section>
				</fieldset>
			</form>
		</article>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include "_/components/php/aside-register.php"; ?>
	</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>