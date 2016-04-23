<?php require_once('../includes/initialize.php');
require_once('../includes/vendor/autoload.php');
if($session->is_logged_in()) {
	$session->message('شما داخل سایت شدید. به منظور ثبت نام لطفا خارج شوید،');
	redirect_to('member');
}
$title    = 'پارس کلیک - ثبت نام';
$filename = basename(__FILE__);
$errors   = '';
if($_POST) {
	if(empty(RECAPTCHASITEKEY) || empty(RECAPTCHASECRETKEY)) {
		$errors = 'کدهای تایید reCaptcha API خالی هستند. لطفا مدیر سایت را در جریان بگذارید.';
	} elseif(isset($_POST['g-recaptcha-response'])) {
		$recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHASECRETKEY);
		$resp      = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if($resp->isSuccess()) {
			if($session->csrf_token_is_valid() && $session->csrf_token_is_recent()) {
				//validations
				if( ! has_presence($_POST['username']) || ! has_presence($_POST['password'])) {
					$errors = 'اسم کاربری و پسورد را خالی نگذارید!';
				} elseif( ! has_length($_POST['username'], ['max' => 20])) {
					$errors = 'اسم کاربری بیشتر از ۲۰ حرف است!';
				} elseif($_POST['password'] !== $_POST['confirm_pass']) {
					$errors = 'پسورد ها مطابقت ندارند!';
				} elseif( ! has_length($_POST['password'], ['min' => 6])) {
					$errors = 'پسورد باید حداقل ۶ حروف یا بیشتر باشد!';
				} elseif( ! has_format_matching($_POST['password'], '/[^A-Za-z0-9]/')) {
					$errors = 'حداقل از یک حرف مخصوص استفاده کنید!';
				} elseif(Member::find_by_username(trim($_POST['username']))) {
					$errors = 'اسم کاربری موجود نیست! لطفا از اسم کاربری دیگری استفاده کنید.';
				} elseif(Member::find_by_email(trim($_POST['email']))) {
					$errors = 'این ایمیل قبلا ثبت شده، لطفااگر جزئیات یادتان نیست آنها را بازیافت کنید.';
				} elseif(is_temp_mail(trim($_POST['email']))) {
					$errors = 'نه دیگه! ثبت نام با ایمیل موقت نداشتیم! ایمیل معتبر وارد کنید.';
				} else {
					global $database;
					$member             = new Member();
					$member->id         = $database->insert_id();
					$member->username   = trim(strtolower($_POST['username']));
					$member->password   = $member->password_encrypt($_POST['password']);
					$member->first_name = trim(ucwords(strtolower($_POST['first_name'])));
					$member->last_name  = trim(ucwords(strtolower($_POST['last_name'])));
					$member->gender     = trim($_POST['gender']);
					$member->address    = trim(ucwords(strtolower($_POST['address'])));
					$member->city       = trim(ucwords(strtolower($_POST['city'])));
					$member->email      = trim(strtolower($_POST['email']));
					$member->status     = 0;
					$member->token      = md5(uniqid(rand()));
					$result             = $member->create();
					if($result) {
						if( ! $member->email_confirmation_details($member->username)) {
							$errors = 'ثبت نام موفقیت آمیز بود اما ایمیل فرستاده نشد! ادمین را این موضوع باخبر کنید';
						} else {
							$session->message('ثبت نام موفقیت آمیز بود. لطفا ایمیل خود را تا ۱۰ دقیقه دیگر چک کنید و تایید کنید. پوشه spam را هم چک کنید.');
							redirect_to('login');
						}
					} else {
						$errors = 'ثبت نام موفق نبود. اشتباهی مانع از انجام ثبت نام شما شد!';
					}
				}
			} else {
				// $errors = "شناسه CSRF معتبر نیست!";
				$session->die_on_csrf_token_failure();
			}
		} else {
			foreach($resp->getErrorCodes() as $code) {
				$errors = "لطفا ثابت کنید ربات نیستید!   کد خطا: {$code}";
			}
		}
	}
} else {
	// Saving fields in case of an error to prevent user to type them again:
	$_POST['username']   = '';
	$_POST['email']      = '';
	$_POST['first_name'] = '';
	$_POST['last_name']  = '';
	$_POST['address']    = '';
	$_POST['city']       = '';
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<?php include_layout_template('article-register.php'); ?>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include_layout_template('aside-login.php'); ?>
	</section>
<?php include_layout_template('footer.php'); ?>