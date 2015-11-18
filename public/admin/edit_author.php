<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
$author   = Author::find_by_id($_GET["id"]);
$errors   = "";
if(!$author) {
	$session->message("نویسنده پیدا نشد!");
	redirect_to("author_list.php");
}
if(isset($_POST['submit'])) {
	$author->id         = (int)$_GET["id"];
	$author->username   = strtolower($_POST["username"]);
	$author->first_name = ucwords(strtolower($_POST["first_name"]));
	$author->last_name  = ucwords(strtolower($_POST["last_name"]));
	$author->email      = strtolower($_POST["email"]);
	$author->status     = (int)$_POST["status"];
	$result             = $author->save();
	if($result) { // Success
		$session->message("نویسنده بروزرسانی شد.");
		redirect_to("author_list.php");
	} else { // Failure
		$errors = "نتوانستیم نویسنده را بروزرسانی کنیم یا اینکه شما چیزی عوض نکردید.";
	}
} else {
}
 include_layout_template("admin_header.php");
 include("../_/components/php/admin_nav.php");
 echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square-o"></i> ویرایش نویسنده</h2>

			<form class="form-horizontal" action="edit_author.php?id=<?php echo urlencode($author->id); ?>" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-user"></i> <?php echo htmlentities(ucwords(strtolower($author->full_name()))); ?></legend>
					<!--username-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username" value="<?php echo htmlentities($author->username); ?>"/>
						</div>
					</section>
					<!--password-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد</label>
						<div class="controls">
							<input readonly class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="Password encrypted"/>
						</div>
					</section>
					<!--first_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="نام" value="<?php echo htmlentities($author->first_name); ?>"/>
						</div>
					</section>
					<!--last_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="نام خانوادگی" value="<?php echo htmlentities($author->last_name); ?>"/>
						</div>
					</section>
					<!--email-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="email" id="post_code" placeholder="Email" value="<?php echo htmlentities($author->email); ?>"/>
						</div>
					</section>
					<!--status-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="status">فعال</label>
						<div class="controls">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="status" id="inlineRadioNo" value="0" <?php if($author->status == 0) { echo "checked"; } ?> /> خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="1" <?php if($author->status == 1) { echo "checked"; } ?> /> بله
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="2" <?php if($author->status == 2) { echo "checked"; } ?> /> مسدود
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="author_list.php">لغو</a>
							<a class="btn btn-info" href="delete_author.php?id=<?php echo urlencode($author->id); ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlentities(ucwords(strtolower($author->full_name()))); ?>?');">
								حذف
							</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								ویرایش
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>عکس پروفایل</h2>
			<?php if(empty($author->photo)) { ?>
				<p class="text-muted center">عکس پروفایل موجود نیست.</p>
			<?php } else { ?>
				<img class="img-responsive img-thumbnail" style="height: 200px; width: 200px;" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>">
			<?php } ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>