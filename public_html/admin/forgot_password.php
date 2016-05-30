<?php require_once('../../includes/initialize.php');
$errors   = '';
$username = '';
if (isset($_POST['submit'])) {
	$username = trim($_POST['username']);
	if (has_presence($username)) {
		$admin  = Admin::find_by_username($username);
		$author = Author::find_by_username($username);
		if ($admin) {
			$admin->create_reset_token($username);
			if ( ! $admin->email_reset_token($username)) {
				$errors = 'خطا! ایمیل فرستاده نشد!';
			}
		} elseif ($author) {
			$author->create_reset_token($username);
			if ( ! $author->email_reset_token($username)) {
				$errors = 'خطا! ایمیل فرستاده نشد!';
			}
		}
		# Username was not found; don't do anything for security reasons
		# because if we notify user that username was not found,
		# they can try to find out what username is a valid username.
		$message = 'لینکی برای بازیافت پسورد شما به آدرس ایمیلی فرستاده شد که هنگام ثبت نام وارد کردید. تا ۱۰ دقیقه دیگه ایمیلتون رو چک کنید.';
	} else {
		$errors = 'لطفا اسم کاربری را وارد کنید.';
	}
}
?>
<?php include_layout_template('admin_header.php'); ?>
	<header class="clearfix">
		<section id="branding">
			<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Admin Area"></a>
		</section>
	</header>
	<hr/>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-key"></i> قسمت فراموش کردن پسورد</h2>
			<br/>
			<form class="form-horizontal" action="forgot_password.php" method="POST" accept-charset="utf-8" data-remote>
				<fieldset>
					<legend>اسم کاربری شما چیست؟</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
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
							<a href="index.php" class="btn btn-danger">لغو</a>
							<button class="btn btn-primary" name="submit" id="submit" type="submit"
							        data-loading-text="چند لحظه صبر کنید <i class='fa fa-spinner fa-pulse'></i>">
							برو
							</button>
						</div>
					</section>
					<section class="row">
						<p class="col-sm-offset-4 col-md-offset-4 col-lg-offset-4 alert alert-info">
							<i class="fa fa-info-circle"></i>
							اگر اسم کاربری یادتان نیست با مدیر ارشد سایت از ایمیلی که ثبت نام کردید تماس بگیرید تا اسم کاربری به ایمیل
							شما فرستاده شود.
						</p>
					</section>
				</fieldset>
			</form>
		</article>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	</section><!-- sidebar -->
<?php include_layout_template('admin_footer.php'); ?>