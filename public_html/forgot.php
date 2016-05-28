<?php require_once('../includes/initialize.php');
$title    = 'پارس کلیک - فراموشی پسورد';
$filename = basename(__FILE__);
$username = '';
$errors   = '';
if (isset($_POST['submit'])) {
	$username = trim($_POST['username']);
	if (has_presence($username)) {
		$user = Member::find_by_username($username);
		if ($user) {
			$user->create_reset_token($username);
			if ( ! $user->email_reset_token($username)) {
				$errors = 'خطا! ایمیل فرستاده نشد.';
			}
		}
		# Message returned is the same whether the user
		# was found or not, so that we don't reveal which
		# user names exist and which do not.
		$message = 'ایمیلی دارای اسم کاربری شما به آدرسی فرستاده شد که در دیتابیس موجود است. تا ۵ دقیقه دیگه ایمیلتون رو چک کنید.';
	} else {
		$errors = 'لطفا اسم کاربری را وارد کنید.';
	}
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-key"></i> بازیافت پسورد </h2>
			<br/>
			<form class="form-horizontal" action="forgot" method="POST" accept-charset="utf-8">
				<p class="text-danger">
					لطفا توجه کنید که سرور بعضی وقتها خیلی شلوغ هست پس فقط یک بار درخواست بدید ولی اگر تا ۱۰ دقیقه ایمیل دریافت
					نکردید مدیر سایت رو آگاه کنید.
				</p>
				<fieldset>
					<legend>اسم کاربری شما چیست؟</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">
							اسم کاربری &nbsp;
						</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon arial"><span class="glyphicon glyphicon-user"></span></span>
								<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username"
								       autofocus placeholder="Username" value="<?php echo htmlentities($username); ?>" maxlength="30"
								       required/>
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
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include_layout_template('aside-register.php'); ?>
	</section>
<?php include_layout_template('footer.php'); ?>