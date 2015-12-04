<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - حساب کاربری";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$errors = "";
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<article>
		<h2>
			<!--<img class="img-thumbnail text-center" src="http://gravatar.com/avatar/--><?php //echo md5($member->email); ?><!--" alt="--><?php //echo $member->email; ?><!--">-->
			حساب کاربری <?php echo ucwords(strtolower($member->full_name())); ?></h2>
		<dl class="dl-horizontal">
			<dt>اسم کاربری:</dt>
			<dd class="arial"><?php echo htmlentities($member->username); ?></dd>
			<dt>نام:</dt>
			<dd><?php echo htmlentities(ucfirst($member->first_name)); ?></dd>
			<dt>نام خانوادگی:</dt>
			<dd><?php echo htmlentities(ucfirst($member->last_name)); ?></dd>
			<dt>جنس:</dt>
			<dd><?php echo !empty($member->gender) ? htmlentities(ucfirst($member->gender)) : "-"; ?></dd>
			<dt>کشور:</dt>
			<dd><?php echo !empty($member->address) ? htmlentities(ucfirst($member->address)) : "-"; ?></dd>
			<dt>شهر:</dt>
			<dd><?php echo !empty($member->city) ? htmlentities(ucfirst($member->city)) : "-"; ?></dd>
			<dt>کد پستی:</dt>
			<dd><?php echo !empty($member->post_code) ? htmlentities(strtoupper($member->post_code)) : "-"; ?></dd>
			<dt>تلفن:</dt>
			<dd><?php echo !empty($member->phone) ? htmlentities($member->phone) : "-"; ?></dd>
			<dt>ایمیل:</dt>
			<dd class="arial"><?php echo !empty($member->email) ? htmlentities(strtolower($member->email)) : "-"; ?></dd>
			<dt>&nbsp;</dt>
			<dd>
				<a href="member-edit-profile" class="btn btn-primary">ویرایش</a>
			</dd>
		</dl>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2>عکس پروفایل</h2>
		<?php if(empty($member->photo)) { ?>
			<div class="">
				<span class="glyphicon glyphicon-user center" style="font-size: 150px;"></span>
			</div>
		<?php } else { ?>
			<img class="img-responsive img-thumbnail center" width="200" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($member->photo); ?>">
		<?php } ?>
		<h2>حذف عضویت</h2>
		<p>با کلیلک روی این دگمه حساب کاربری شما به کلی حذف خواهد شد. بنابراین مواظب باشید اگر می خواهید روی این دگمه کلیک
		   کنید.</p>
		<a class="btn btn-small btn-danger" href="member-permanent-deletion" role="button" onclick="return confirm('آیا مطمئن هستید که می خواهید حق اشتراک شما ابدا پاک شود؟\nبا این کار شما از پایگاه داده ها یه کلی حذف خواهید شد و بی هیچ پیامی به صفحه ورود خواهید رفت!')">
			<i class="fa fa-exclamation-triangle"></i>
			حق اشتراک را بکلی پاک کن!
		</a>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
