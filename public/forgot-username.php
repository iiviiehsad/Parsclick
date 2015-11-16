<?php require_once("../includes/initialize.php");
$filename = basename(__FILE__); ?>
<?php
$errors = "";
if(isset($_POST["submit"])) {
	$email = strtolower($_POST['email']);
	if(has_presence($email)) {
		// Search our fake database to retrieve the user data
		$user = Member::find_by_email($email);
		if($user) {
			// Username was found; okay to reset
			$email = $user->email_username($email);
			if($email) {
				$message = "ایمیل فرستاده شد.";
			} else {
				$errors = "خطا! ایمیل فرستاده نشد.";
			}
		} else {
			// Username was not found; don't do anything
		}
		// Message returned is the same whether the user
		// was found or not, so that we don't reveal which
		// usernames exist and which do not.
		$message = "ایمیلی دارای اسم کاربری شما به آدرسی فرستاده شد که در دیتابیس موجود است.";
	} else {
		$message = "لطفا اسم کاربری را وارد کنید.";
	}
} else {
	$email = "";
}
?>
<?php include_layout_template("header.php"); ?>
<?php include "_/components/php/nav.php"; ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-user"></i> یادآوری اسم کاربری </h2>
			<br/>

			<form class="form-horizontal" action="forgot-username" method="POST" accept-charset="utf-8">
				<fieldset>
					<legend>ایمیل شما چیست؟</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email"> ایمیل &nbsp;</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon arial"><span class="glyphicon glyphicon-user"></span></span>
								<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="email" name="email" id="email" autofocus placeholder="Email" maxlength="50" required/>
							</div>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
						<div class="controls">
							<a href="forgot" class="btn btn-danger">لغو</a>
							<button class="btn btn-primary" name="submit" id="submit" type="submit">فرستادن</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include "_/components/php/aside-register.php"; ?>
	</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>