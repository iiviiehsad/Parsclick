<?php require_once("../../includes/initialize.php");
$token  = $_GET['token'];
$errors = "";
// Confirm that the token sent is valid
$admin  = Admin::find_by_token($token);
$author = Author::find_by_token($token);
if( ! $token) {
	redirect_to('forgot_password.php');
}
if(isset($_POST["submit"])) {
	$password         = $_POST['password'];
	$password_confirm = $_POST['password_confirm'];
	if( ! has_presence($password) || ! has_presence($password_confirm)) {
		$errors = "جفت پسوردها را پر کنید و خالی نگذارید.";
	} elseif( ! has_length($password, ['min' => 6])) {
		$errors = "پسورد باید حداقل شش حروف یا بیشتر باشد.";
	} elseif( ! has_format_matching($password, '/[^A-Za-z0-9]/')) {
		$errors = "پسورد باید حداقل شامل یک حرفی باشد که نه حروف و نه عدد باشد: مثلا ستاره";
	} elseif($password !== $password_confirm) {
		$errors = "پسوردها با همدیگر یکی نیستند.";
	} else {
		if($admin) {
			$admin->password = $admin->password_encrypt($_POST["password"]);
			$result          = $admin->update();
			if($result) {
				$admin->delete_reset_token($admin->username);
				$session->message("متشکریم! پسورد با موفقیت عوض شد. شما الآن قادر به ورود هستید.");
				redirect_to('index.php');
			} else {
				$errors = "متاسفانه نتوانستیم پسورد بروزرسانی کنیم!";
			}
		} elseif($author) {
			$author->password = $author->password_encrypt($_POST["password"]);
			$result           = $author->update();
			if($result) {
				$author->delete_reset_token($author->username);
				$session->message("متشکریم! پسورد با موفقیت عوض شد. شما الآن قادر به ورود هستید.");
				redirect_to('index.php');
			} else {
				$errors = "متاسفانه نتوانستیم پسورد بروزرسانی کنیم!";
			}
		} else {
			// if couldn't find any admin based in the token in URL
			$session->message("مدت زمانی رمز بپایان رسید! لطفا بعدا دوباره سعی کنید.");
			redirect_to('forgot_password.php');
		}
	}
} else { // end: if(isset($_POST["submit"]))
}
include_layout_template("admin_header.php");
?>
	<header class="clearfix">
		<section id="branding">
			<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Admin Area"></a>
		</section>
	</header>
	<hr/>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>بازیافت پسورد</h2>
			<p class="text-success">پسورد باید حداقل شش حروف یا بیشتر باشد.</p>
			<p class="text-success">پسورد باید حداقل شامل یک حرفی باشد که نه حروف و نه عدد باشد مثل: (!@£$%^&*-+)</p>

			<form class="form-horizontal" action="reset_password.php?token=<?php echo urlencode($token); ?>" method="POST" accept-charset="utf-8">
				<fieldset>
					<legend>پسورد جدید را قرار دهید
						<span class="pull-left wow infinite flash" data-wow-duration="3s" id="confirmMessage"></span></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد جدید</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input onkeyup="checkPass();" class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" autofocus placeholder="New Password" required pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
							</div>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="confirm_pass">تایید پسوردجدید</label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input onkeyup="checkConfirmPass();" class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password_confirm" id="confirm_pass" placeholder="Confirm New Password" required pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
							</div>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
						<div class="controls">
							<a href="index.php" class="btn btn-danger">لغو</a>
							<button class="btn btn-primary" name="submit" id="submit" type="submit">عوض کن
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	</section><!-- sidebar -->
<?php include_layout_template("admin_footer.php"); ?>