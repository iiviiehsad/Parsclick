<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - ویرایش حساب کاربری";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$errors = "";
if(isset($_POST['submit'])) {
	$member->id       = $session->id;
	//$member->username = trim($_POST["username"]);
	if(!empty($_POST["password"])) {
		if(!has_length($_POST["password"], ['min' => 6])) {
			$errors = "پسورد باید حداقل ۶ حروف یا بیشتر باشد!";
		} elseif(!has_format_matching($_POST["password"], '/[^A-Za-z0-9]/')) {
			$errors = "حداقل از یک حرف مخصوص استفاده کنید!";
		} else {
			$member->hashed_password = $member->password_encrypt(trim($_POST["password"]));
		}
	}
	$member->first_name = trim($_POST["first_name"]);
	$member->last_name  = trim($_POST["last_name"]);
	$member->last_name  = trim($_POST["last_name"]);
	$member->gender     = trim($_POST["gender"]);
	$member->address    = trim($_POST["address"]);
	$member->city       = trim($_POST["city"]);
	$member->post_code  = trim($_POST["post_code"]);
	$member->phone      = trim($_POST["phone"]);
	$member->email      = trim($_POST["email"]);
	$result             = $member->save();
	if($result) {
		$session->message("پروفایل بروزرسانی شد.");
		redirect_to("member-profile");
	} else {
		$errors = "بروزرسانی پروفایل موفقیت آمیز نبود یا چیزی اصلا تغییر داده نشد!";
	}
} else {
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<article>
		<h2><i class="fa fa-pencil-square"></i> ویرایش پروفایل </h2>

		<form class="registration form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
			<fieldset id="login">
				<legend><i class="fa fa-user"></i> <?php echo ucwords(strtolower($member->full_name())); ?>
					<span class="pull-left wow flash infinite" data-wow-duration="3s" id="confirmMessage"></span>
				</legend>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="username"> اسم کاربری &nbsp;</label>
					<div class="controls">
						<input onblur="checkUser();" onkeyup="checkUser();" class="arial col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username" disabled required value="<?php echo htmlentities($member->username); ?>"/>
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
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="gender"> جنس &nbsp;</label>
					<div class="controls">
						<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8" name="gender" id="gender">
							<?php echo htmlentities($member->gender); ?>
							<?php if($member->gender === "مرد") { ?>
								<option value="مرد">مرد</option>
								<option value="زن">زن</option>
							<?php } elseif($member->gender === "زن") { ?>
								<option value="زن">زن</option>
								<option value="مرد">مرد</option>
							<?php } else { ?>
								<option disabled value="">لطفا برگزینید</option>
								<option value="مرد">مرد</option>
								<option value="زن">مرد</option>
							<?php } ?>
						</select>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="address"> کشور &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="address" id="address" placeholder="کشور" value="<?php echo htmlentities($member->address); ?>"/>
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
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="post_code" id="post_code" placeholder="کد پستی" value="<?php echo htmlentities($member->post_code); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="phone"> تلفن &nbsp;</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="tel" name="phone" id="phone" placeholder="تلفن" value="<?php echo htmlentities($member->phone); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="email"> ایمیل &nbsp;</label>
					<div class="controls">
						<input onblur="checkEmail();" onkeyup="checkEmail();" class="arial col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="email" name="email" id="email" placeholder="Email" required value="<?php echo htmlentities($member->email); ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$"/>
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
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside class="members_menu">
		<h2><i class="fa fa-picture-o"></i> آواتار</h2>
		<img class="img-thumbnail center" src="http://gravatar.com/avatar/<?php echo md5($member->email); ?>?s=250&d=<?php echo DOMAIN .
		                                                                                                                        DS .
		                                                                                                                        'images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->email; ?>">

		<h2><i class="fa fa-info-circle"></i> اطلاعات</h2>
		<div class="alert alert-info">
			<small>
				اگر مایل به تغییر پسورد نیستید، لطفا پسورد را خالی بگذارید اما اگر مایل به تغییر پسورد قبلی به پسورد جدید
				هستید، لطفا پسورد جدید خود را داخل قسمت پسورد وارد نمایید تا پسورد عوض شود.
			</small>
		</div>
		<div class="alert alert-info">
			<small>
				لطفا ایمیل خود را بروز نگه دارید چرا که تمامی اطلاعات، خبرنامه، بازیافت پسورد یا اسم کاربری و شناسه
				کاربری و پرداختی شما همه به ایمیلی که وارد کردید رابطه دارند. مسئولیت اشتباه وارد کردن ایمیل به عهده
				خودتان است
			</small>
		</div>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
