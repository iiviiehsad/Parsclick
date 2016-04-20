<?php require_once('../includes/initialize.php');
$filename = basename(__FILE__);
$title    = 'پارس کلیک - حساب کاربری';
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$errors = "";
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<article>
		<h2>
			پروفایل <?php echo ucwords(strtolower($member->full_name())); ?>
		</h2>
		<dl class="dl-horizontal">
			<dt>اسم کاربری:</dt>
			<dd class="arial"><?php echo htmlentities($member->username); ?></dd>
			<dt>نام:</dt>
			<dd><?php echo htmlentities(ucfirst($member->first_name)); ?></dd>
			<dt>نام خانوادگی:</dt>
			<dd><?php echo htmlentities(ucfirst($member->last_name)); ?></dd>
			<dt>جنس:</dt>
			<dd><?php echo ! empty($member->gender) ? htmlentities(ucfirst($member->gender)) : "-"; ?></dd>
			<dt>کشور:</dt>
			<dd><?php echo ! empty($member->address) ? htmlentities(ucfirst($member->address)) : "-"; ?></dd>
			<dt>شهر:</dt>
			<dd><?php echo ! empty($member->city) ? htmlentities(ucfirst($member->city)) : "-"; ?></dd>
			<dt>ایمیل:</dt>
			<dd class="arial"><?php echo ! empty($member->email) ? htmlentities(strtolower($member->email)) : "-"; ?></dd>
			<dt>&nbsp;</dt>
			<dd><a href="member-edit-profile" class="btn btn-primary">ویرایش</a></dd>
			<dt>&nbsp;</dt>
			<dd>
				<div class="alert alert-info">
					<i class="fa fa-info-circle fa-3x"></i> اگر آواتار یا عکس پروفایل ندارید
					<a target="_blank" data-toggle="tooltip" title="آپلود کنید" href="http://fa.gravatar.com/">
						<span class="underline">اینجا رو کلیک کنید</span>
					</a>تا آپلود کنید. از شما خواهش می کنم که حتما این کار را انجام دهید.
				</div>
			</dd>
		</dl>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2>آواتار</h2>
		<img class="img-thumbnail center"
		     src="http://gravatar.com/avatar/<?php echo md5($member->email); ?>?s=200&d=<?php echo DOMAIN . DS . 'images/misc/default-gravatar-pic.png'; ?>"
		     alt="<?php echo $member->email; ?>"/>
		<h2>حذف عضویت</h2>
		<p>با کلیلک روی این دگمه حساب کاربری شما به کلی حذف خواهد شد. بنابراین مواظب باشید اگر می خواهید روی این دگمه کلیک
		   کنید.</p>
		<a class="btn btn-small btn-danger confirmation" href="member-permanent-deletion" role="button">
			<i class="fa fa-exclamation-triangle"></i>
			حق اشتراک را بکلی پاک کن!
		</a>
	</aside>
</section>
<?php include_layout_template('footer.php'); ?>
