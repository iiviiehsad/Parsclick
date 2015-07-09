<?php require_once("../../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $session->confirm_author_logged_in(); ?>
<?php $author = Author::find_by_id($session->id); ?>
<?php $author->check_status(); ?>
<?php $author = Author::find_by_id($session->id); ?>
<?php $author->check_status(); ?>
<?php
if(isset($_POST['submit'])) {
	$author->id       = $session->id;
	$author->username = trim($_POST["username"]);
	if(!empty($_POST["password"])) {
		$author->password = $author->password_encrypt(trim($_POST["password"]));
	}
	$author->first_name = trim($_POST["first_name"]);
	$author->last_name  = trim($_POST["last_name"]);
	$author->email      = trim($_POST["email"]);
	if(isset($_FILES["photo"]["tmp_name"]) && ($_FILES["photo"]["tmp_name"]) !== NULL && !empty($_FILES["photo"]["tmp_name"])) {
		$author->photo = file_get_contents($_FILES["photo"]["tmp_name"]);
	}
	$result = $author->save();
	if($result) {
		// Success
		$session->message("پروفایل شما بروزرسانی شد.");
		redirect_to("author_profile.php");
	} else {
		// Failure
		$errors = "پروفایل شما بروزرسانی نشد!";
	}
} else {
	$errors = "";
} // end: if (isset($_POST['submit']))
?>
<?php include_layout_template("admin_header.php"); ?>
<?php include("../_/components/php/author_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<h2><i class="fa fa-pencil-square-o"></i> ویرایش پروفایل</h2>

		<form class="registration form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<fieldset id="login">
				<legend><i class="fa fa-user"></i> <?php echo ucwords(strtolower($author->full_name())); ?></legend>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username" required value="<?php echo htmlentities($author->username); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="New Password" value=""/>
					</div>
				</section>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="نام" required value="<?php echo htmlentities($author->first_name); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="نام خانوادگی" required value="<?php echo htmlentities($author->last_name); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="email" name="email" id="email" placeholder="Email" required value="<?php echo htmlentities($author->email); ?>"/>
					</div>
				</section>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="photo">عکس پروفایل</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="5000000">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="file" name="photo" id="photo"
						<br/><span>اندازه: ۵ مگابایت</span>
					</div>
				</section>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
					<div class="controls">
						<a class="btn btn-danger" href="author_profile.php">لغو</a>
						<button class="btn btn-success" name="submit" id="submit" type="submit">ویرایش</button>
					</div>
				</section>
			</fieldset>
		</form>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="members_menu">
		<h2><i class="fa fa-picture-o"></i> عکس پروفایل</h2>
		<?php if(empty($author->photo)) { ?>
			<span class="glyphicon glyphicon-user center" style="font-size: 150px; margin: 0; padding: 0;"></span>
			<span class="text-muted center">عکس پروفایل موجود نیست</span>
			<hr/>
			<small class="text-muted center">
				برای آپلود کردن عکس پروفایل به پایین صفحه بروید و روی قسمت عکس پروفایل کلیک کنید و فیلی نهایتا به اندازه
				ی ۵ مگابایت یا کمتر آپلود کنید. لطفا توجه داشته باشید که عکس پروفایل شما همراه با مقالات و دروسی که می
				سازید کنار نام شما دیده خواهد شد. بنابراین دقت در انتخاب عکستان داشته باشید. عکس مورد نظر باید صورت شما
				را نمایان نشان دهد. عکس های نا مناسب یا عکس هایی که به شما متعلق نیست باعث معوق شدن ناگهانی حساب شما
				خواهد شد.
			</small>
		<?php } else { ?>
			<img class="img-responsive img-thumbnail" height="200" width="200" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>">
			<br/>
			<a class="btn btn-default btn-small" href="remove_photo.php?id=<?php echo urlencode($author->id); ?>" onclick="return confirm('آیا مطمئن به حذف کردن عکس پروفایل خود هستید؟')">
				حذف عکس پروفایل
			</a>
		<?php } ?>
	</aside>
</section>
<?php include_layout_template("admin_footer.php"); ?>
