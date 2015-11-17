<?php
require_once("../includes/initialize.php");
if($session->is_logged_in()) {
	$session->message("شما داخل سایت شدید. به منظور ثبت نام لطفا خارج شوید،");
	redirect_to("member");
}
$filename = basename(__FILE__);
$errors   = "";
if($_POST) {
	if($session->csrf_token_is_valid() && $session->csrf_token_is_recent()) {
		//validations
		if(!has_presence($_POST["username"]) || !has_presence($_POST["password"])) {
			$errors = "اسم کاربری و پسورد را خالی نگذارید! (هیچ مبلغی کم نشد)";
		} elseif($_POST["password"] !== $_POST["confirm_pass"]) {
			$errors = "پسورد ها مطابقت ندارند! (هیچ مبلغی کم نشد)";
		} elseif(!has_length($_POST["password"], ['min' => 6])) {
			$errors = "پسورد باید خراقل ۶ حروف یا بیشتر باشد! (هیچ مبلغی کم نشد)";
		} elseif(!has_format_matching($_POST["password"], '/[^A-Za-z0-9]/')) {
			$errors = "حداقل از یک حرف مخصوص استفاده کنید! (هیچ مبلغی کم نشد)";
		} elseif(Member::find_by_username(trim($_POST["username"]))) {
			$errors = "اسم کاربری موجود نیست! لطفا از اسم کاربری دیگری استفاده کنید.";
		} else {
			global $database;
			$member                  = new Member();
			$member->id              = $database->insert_id();
			$member->username        = trim(strtolower($_POST["username"]));
			$member->hashed_password = $member->password_encrypt($_POST["password"]);
			$member->first_name      = trim(ucwords(strtolower($_POST["first_name"])));
			$member->last_name       = trim(ucwords(strtolower($_POST["last_name"])));
			$member->gender          = trim($_POST["gender"]);
			$member->address         = trim(ucwords(strtolower($_POST["address"])));
			$member->city            = trim(ucwords(strtolower($_POST["city"])));
			$member->post_code       = trim(strtoupper($_POST["post_code"]));
			$member->phone           = trim($_POST["phone"]);
			$member->email           = trim(strtolower($_POST["email"]));
			$member->photo           = NULL;
			$member->status          = 0;
			$member->token           = md5(uniqid(rand()));
			$result                  = $member->create();
			if($result) {
				$session->message("با تشکر! ثبت نام موفقیت آمیز بود خیلی مهم هست که آدرس ایمیل خودتون رو درست وارد کرده باشید و این آدرس بروز باشه. اگر این آدرس درست نیست, همین الآن درستش کنید.");
				$member->email_confirmation_details($member->username);
				redirect_to("login");
			} else {
				$errors = "ثبت نام موفق نبود. اشتباهی مانع از انجام ثبت نام شما شد!";
			}
		}
	} else {
		$errors = "شناسه CSRF معتبر نیست!";
	}
} else {
	// Saving fields in case of an error to prevent user to type them again:
	$_POST["username"]   = "";
	$_POST["email"]      = "";
	$_POST["first_name"] = "";
	$_POST["last_name"]  = "";
	$_POST["address"]    = "";
	$_POST["city"]       = "";
	$_POST["post_code"]  = "";
	$_POST["phone"]      = "";
}
?>
<?php include("../includes/layouts/header.php"); ?>
<?php include "_/components/php/nav.php"; ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-md-8 col-lg-8">
		<?php include "_/components/php/article-register.php"; ?>
	</section>
	<section class="sidebar col-md-4 col-lg-4">
		<?php include "_/components/php/aside-login.php"; ?>
	</section>
<?php include_layout_template("footer.php"); ?>