<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
$member   = Member::find_by_id($_GET["id"]);
if(!$member) {
	$session->message("Member could not be found!");
	redirect_to("manage_members.php");
}
$errors = "";
if(isset($_POST['submit'])) {
	$member->id         = (int)$_GET["id"];
	$member->username   = strtolower($_POST["username"]);
	$member->first_name = ucwords(strtolower($_POST["first_name"]));
	$member->last_name  = ucwords(strtolower($_POST["last_name"]));
	$member->gender     = ucfirst($_POST["gender"]);
	$member->address    = ucwords(strtolower($_POST["address"]));
	$member->city       = ucwords(strtolower($_POST["city"]));
	$member->post_code  = strtoupper($_POST["post_code"]);
	$member->phone      = $_POST["phone"];
	$member->email      = strtolower($_POST["email"]);
	//$member->photo;
	$member->status = (int)$_POST["status"];
	$result         = $member->save();
	if($result) { // Success
		$session->message("عضویت بروزرسانی شد.");
		redirect_to("member_list.php");
	} else { // Failure
		$errors = "عضویت بروزرسانی نشد.";
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message, $errors);
?>
	<section class="main col-xs-12 col-sm-12 col-md-9 col-lg-9">
		<article>
			<h2><i class="fa fa-pencil-square"></i> ویرایش عضویت </h2>

			<form class="form-horizontal" action="edit_member.php?id=<?php echo urlencode($member->id); ?>" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-user"></i> <?php echo htmlentities(ucwords(strtolower($member->full_name()))); ?></legend>
					<!--username-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username"> اسم کاربری &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username in English" value="<?php echo htmlentities($member->username); ?>"/>
						</div>
					</section>
					<!--password-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password"> پسورد &nbsp;</label>
						<div class="controls">
							<input disabled class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="Password is encrypted"/>
						</div>
					</section>
					<!--first_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name"> نام &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="نام" value="<?php echo htmlentities($member->first_name); ?>"/>
						</div>
					</section>
					<!--last_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name"> نام خانوادگی&nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="نام خانوادگی" value="<?php echo htmlentities($member->last_name); ?>"/>
						</div>
					</section>
					<!--gender-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="gender"> جنس &nbsp;</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="gender" id="gender">
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
					<!--address-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="address"> کشور &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="address" id="address" placeholder="کشور" value="<?php echo htmlentities($member->address); ?>"/>
						</div>
					</section>
					<!--city-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="city"> شهر &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="city" id="address" placeholder="شهر" value="<?php echo htmlentities($member->city); ?>"/>
						</div>
					</section>
					<!--post_code-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="post_code"> کد پستی &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="post_code" id="post_code" placeholder="کد پستی" value="<?php echo htmlentities($member->post_code); ?>"/>
						</div>
					</section>
					<!--phone-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="phone"> تلفن &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="tel" name="phone" id="post_code" placeholder="تلفن" value="<?php echo htmlentities($member->phone); ?>"/>
						</div>
					</section>
					<!--email-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email"> ایمیل &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="email" id="post_code" placeholder="ایمیل" value="<?php echo htmlentities($member->email); ?>"/>
						</div>
					</section>
					<!--status-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="status"> فعال &nbsp;</label>
						<div class="controls">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="status" id="inlineRadioNo" value="0"
										<?php if($member->status == 0) { echo "checked"; } ?> /> منتظر دریافت ایمیل
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="1"
										<?php if($member->status == 1) { echo "checked"; } ?> /> بله
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="2"
										<?php if($member->status == 2) { echo "checked"; } ?> /> خیر
							</label>
						</div>
						<!--buttons-->
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="member_list.php">لغو</a>
							<a class="btn btn-info" href="delete_member.php?id=<?php echo urlencode($member->id); ?>" onclick="return confirm('آیا مطمئن به پاک کردن <?php echo htmlentities(ucwords(strtolower($member->full_name()))); ?> هستید؟');">
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
	<section class="sidebar col-xs-12 col-sm-12 col-md-3 col-lg-3">
		<aside>
			<h2>آواتار</h2>
			<img class="img-circle center" src="http://gravatar.com/avatar/<?php echo md5($member->email); ?>?s=300&d=<?php echo 'http://' . DOMAIN . DS . 'images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->email; ?>">
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>