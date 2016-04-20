<?php require_once('../includes/initialize.php');
$filename = basename(__FILE__);
$token    = $_GET['token'];
// Confirm that the token sent is valid
$user = Member::find_by_token($token);
if( ! $token || ! $user) {
	// Token wasn't sent or didn't match a user.
	redirect_to('forgot');
}
$errors = '';
if(isset($_POST['submit'])) {
	$password         = $_POST['password'];
	$password_confirm = $_POST['password_confirm'];
	if( ! has_presence($password) || ! has_presence($password_confirm)) {
		$errors = 'جفت پسوردها را پر کنید و خالی نگذارید.';
	} elseif( ! has_length($password, ['min' => 6])) {
		$errors = 'پسورد باید حداقل شش حروف یا بیشتر باشد.';
	} elseif( ! has_format_matching($password, '/[^A-Za-z0-9]/')) {
		$errors = 'پسورد باید حداقل شامل یک حرفی باشد که نه حروف و نه عدد باشد: مثلا ستاره';
	} elseif($password !== $password_confirm) {
		$errors = 'پسوردها با همدیگر یکی نیستند.';
	} else {
		$user->hashed_password = $user->password_encrypt($_POST['password']);
		$result                = $user->update();
		if($result) {
			$user->delete_reset_token($user->username);
			$session->message('پسورد با موفقیت عوض شد.');
			//$session->login($user);
			redirect_to('login');
		} else {
			$errors = 'متاسفانه نتوانستیم پسورد بروزرسانی کنیم!';
		}
	}
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>بازیافت پسورد</h2>
			<p class="text-success">پسورد باید حداقل شش حروف یا بیشتر باشد.</p>
			<p class="text-success">پسورد باید حداقل شامل یک حرفی باشد که نه حروف و نه عدد باشد مثل: (!@£$%^&*-+)</p>

			<form class="form-horizontal" action="reset-password?token=<?php echo urlencode($token); ?>" method="POST"
			      accept-charset="utf-8">
				<fieldset>
					<legend>پسورد جدید را قرار دهید
						<span class="pull-left wow infinite flash" data-wow-duration="3s" id="confirmMessage"></span>
					</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد
						                                                                                 جدید</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input onkeyup="checkPass();" class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password"
								       name="password" id="password" autofocus placeholder="New Password" required
								       pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
							</div>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="confirm_pass">تایید پسورد
						                                                                                     جدید</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input onkeyup="checkConfirmPass();" class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password"
								       name="password_confirm" id="confirm_pass" placeholder="Confirm New Password" required
								       pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
							</div>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
						<div class="controls">
							<a href="login" class="btn btn-danger">لغو</a>
							<button class="btn btn-primary" name="submit" id="submit" type="submit">برو
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include_layout_template('aside-register.php'); ?>
	</section><!-- sidebar -->
<?php include_layout_template('footer.php'); ?>