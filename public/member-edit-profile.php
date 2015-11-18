<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - ویرایش حساب کاربری";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$MAX_FILE_SIZE      = 100000;
$allowed_mime_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
$allowed_extensions = ['png', 'gif', 'jpg', 'jpeg'];
$errors             = "";
if(isset($_POST['submit'])) {
	$member->id       = $session->id;
	$member->username = trim($_POST["username"]);
	if(!empty($_POST["password"])) {
		$member->hashed_password = $member->password_encrypt(trim($_POST["password"]));
	}
	$member->first_name = trim($_POST["first_name"]);
	$member->last_name  = trim($_POST["last_name"]);
	$member->address    = trim($_POST["address"]);
	$member->city       = trim($_POST["city"]);
	$member->post_code  = trim($_POST["post_code"]);
	$member->phone      = trim($_POST["phone"]);
	$member->email      = trim($_POST["email"]);
	if(!empty($_FILES["photo"]["name"])) {
		$file_extension = file_extension($_FILES["photo"]['name']);
		if($_FILES["photo"]['error'] > 0) {
			$errors = "خطا: " . file_upload_error($_FILES["photo"]['error']);
		} elseif(!is_uploaded_file($_FILES["photo"]["tmp_name"])) {
			$errors = "مرجع فایل شامل فایلی که بتازگی آپلود کردید نیست!";
		} elseif($_FILES["photo"]["size"] > $MAX_FILE_SIZE) {
			$errors = "اندازه فایل بزرگ است!";
		} elseif(!in_array($_FILES["photo"]["type"], $allowed_mime_types)) {
			$errors = "فایل عکس نیست!";
		} elseif(!in_array($file_extension, $allowed_extensions)) {
			$errors = "فایل عکس نیست!";
		} elseif(file_contains_php($_FILES["photo"]["tmp_name"])) {
			$errors = "فایل دارای پی اچ پی است!";
		} else {
			$member->photo = file_get_contents($_FILES["photo"]["tmp_name"]);
			$result        = $member->save();
			if($result) {
				$session->message("شما پروفیل خود را بروز رساندید.");
				redirect_to("member-profile");
			} else {
				$errors = "بروزرسانی پروفایل موفقیت آمیز نبود!";
			}
		}
	} else {
		$result = $member->save();
		if($result) {
			$session->message("شما پروفیل خود را بروز رساندید.");
			redirect_to("member-profile");
		} else {
			$errors = "بروزرسانی پروفایل موفقیت آمیز نبود!";
		}
	}
} else {
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<h2><i class="fa fa-pencil-square-o"></i> ویرایش پروفایل </h2>

		<form class="registration form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<fieldset id="login">
				<legend><i class="fa fa-user"></i> <?php echo ucwords(strtolower($member->full_name())); ?>
					<span class="pull-left wow flash infinite" data-wow-duration="3s" id="confirmMessage"></span>
				</legend>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="username"> اسم کاربری &nbsp;</label>
					<div class="controls">
						<input onblur="checkUser();" onkeyup="checkUser();" class="arial col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username (حروف انگلیسی)" required value="<?php echo htmlentities($member->username); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="password"> پسورد جدید&nbsp;</label>
					<div class="controls">
						<input onblur="checkPass();" onkeyup="checkPass();" class="arial col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="New password" value="" pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="first_name"> نام &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="نام" required value="<?php echo htmlentities($member->first_name); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="last_name"> نام خانوادگی &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="نام خانوادگی" required value="<?php echo htmlentities($member->last_name); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="address"> آدرس &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="address" id="address" placeholder="آدرس" value="<?php echo htmlentities($member->address); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="city"> شهر &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="city" id="city" placeholder="شهر" value="<?php echo htmlentities($member->city); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="post_code"> کد پستی &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="post_code" id="post_code" placeholder="کد پستی" value="<?php echo htmlentities($member->post_code); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="phone"> تلفن &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="tel" name="phone" id="phone" placeholder="تلفن" value="<?php echo htmlentities($member->phone); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="email"> ایمیل &nbsp;</label>
					<div class="controls">
						<input onblur="checkEmail();" onkeyup="checkEmail();" class="arial col-xs-12 col-sm-8 col-md-8 col-lg-8" type="email" name="email" id="email" placeholder="Email" required value="<?php echo htmlentities($member->email); ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$"/>
					</div>
				</section>
				<section class="row">
					<label style="cursor:pointer;" class="col-sm-4 col-md-4 col-lg-4 control-label" for="photo"> عکس
					                                                                                             پروفایل&nbsp;</label>
					<div class="controls">
						<label class="control-label btn btn-small btn-primary" for="photo">
							آپلود عکس
						</label>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $MAX_FILE_SIZE; ?>">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="file" name="photo" id="photo"
						<span>اندازه: ۱۰۰ کیلو بایت</span>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
					<div class="controls">
						<a class="btn btn-danger" href="member-profile">لغو</a>
						<button class="btn btn-success" name="submit" id="submit" type="submit">فرستادن</button>
					</div>
				</section>
			</fieldset>
		</form>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="members_menu">
		<h2><i class="fa fa-picture-o"></i> عکس پروفایل </h2>
		<?php if(empty($member->photo)) { ?>
			<span class="glyphicon glyphicon-user center" style="font-size: 150px; margin: 0; padding: 0;"></span>
			<span class="text-muted center">عکس موجود نیست</span>
		<?php } else { ?>
			<img class="img-responsive img-thumbnail center" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($member->photo); ?>">
			<a class="btn btn-default btn-small center" href="remove-photo" onclick="return confirm('آیا مطمئن به حذف عکس پروفایل خود هستید؟')">
				<span class="glyphicon glyphicon-trash"></span> حذف عکس
			</a>
		<?php } ?>
		<h2><i class="fa fa-info-circle"></i> اطلاعات</h2>
		<div class="well center">
			<small>
				اگر مایل به تغییر پسورد نیستید، لطفا پسورد را خالی بگذارید اما اگر مایل به تغییر پسورد قبلی به پسورد جدید
				هستید، لطفا پسورد جدید خود را داخل قسمت پسورد وارد نمایید تا پسورد عوض شود.
			</small>
		</div>
		<div class="well center">
			<small>
				برای آپلود کردن عکس، روی عکس پروفایل کلیک کنید و عکسی مربع داخل کامپیوتر خود انتخاب کنید که بیشتر ار ۱۰۰
				کیلو بایت نباشد. آپلود عکس برای پروفایل خود ضروری نیست امـــا باعث این خواهد شد که مدیران سایت شما را به
				چهره بشناسند.
			</small>
		</div>
		<div class="well center">
			<small>
				لطفا ایمیل خود را بروز نگه دارید چرا که تمامی اطلاعات، خبرنامه، بازیافت پسورد یا اسم کاربری و شناسه
				کاربری و پرداختی شما همه به ایمیلی که وارد کردید رابطه دارند. مسئولیت اشتباه وارد کردن ایمیل به عهده
				خودتان است
			</small>
		</div>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
