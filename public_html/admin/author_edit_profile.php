<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
$errors             = "";
$MAX_FILE_SIZE      = 100000;
$allowed_mime_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
$allowed_extensions = ['png', 'gif', 'jpg', 'jpeg', 'PNG', 'GIF', 'JPG', 'JPEG'];
if(isset($_POST['submit'])) {
	$author->id       = $session->id;
	//$author->username = trim($_POST["username"]);
	if(!empty($_POST["password"])) {
		$author->password = $author->password_encrypt(trim($_POST["password"]));
	}
	$author->first_name = trim($_POST["first_name"]);
	$author->last_name  = trim($_POST["last_name"]);
	$author->email      = trim($_POST["email"]);
	if($_FILES["photo"]['name']) {
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
			$author->photo = file_get_contents($_FILES["photo"]["tmp_name"]);
			$result        = $author->save();
			if($result) {
				$session->message("پروفایل بروزرسانی شد.");
				redirect_to("author_profile.php");
			} else {
				$errors = "بروزرسانی پروفایل موفقیت آمیز نبود!";
			}
		}
	} else {
		$result = $author->save();
		if($result) {
			$session->message("پروفایل بروزرسانی شد.");
			redirect_to("author_profile.php");
		} else {
			$errors = "بروزرسانی پروفایل موفقیت آمیز نبود!";
		}
	}
} else {
} // end: if (isset($_POST['submit']))
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message, $errors);
?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<article>
		<h2>ویرایش پروفایل</h2>

		<form class="registration form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
			<fieldset id="login">
				<legend><?php echo ucwords(strtolower($author->full_name())); ?></legend>
				<section class="row">
					<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
					<div class="controls">
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username" disabled required value="<?php echo htmlentities($author->username); ?>"/>
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
						<label style="cursor:pointer;" class="control-label btn btn-small btn-primary" for="photo">
							آپلود عکس
						</label>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $MAX_FILE_SIZE; ?>"/>
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="file" name="photo" id="photo" accept="image/*"/>
						&nbsp;&nbsp;&nbsp;<small>اندازه: ۱۰۰ کیلوبایت</small>
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
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2>آواتار</h2>
		<?php if(empty($author->photo)) { ?>
			<span class="glyphicon glyphicon-user center" style="font-size: 150px; margin: 0; padding: 0;"></span>
			<span class="text-muted center">عکس پروفایل موجود نیست</span>
		<?php } else { ?>
			<img class="img-thumbnail center" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>">
			<div class="center">
				<a class="btn btn-default btn-small" href="author_remove_photo.php" onclick="return confirm('آیا مطمئن به حذف کردن عکس پروفایل خود هستید؟')">
					حذف آواتار
				</a>
			</div>
		<?php } ?>
	</aside>
</section>
<?php include_layout_template("admin_footer.php"); ?>
