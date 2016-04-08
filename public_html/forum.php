<?php
require_once('../includes/initialize.php');
$filename = basename(__FILE__);
$title    = 'پارس کلیک - انجمن';
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$errors = '';
$body   = '';
if( ! $current_course || ! $current_category) {
	$current_course = $current_category = Course::find_newest_course();
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article id="comments">
			<h3><i class="fa fa-comments fa-lg"></i> انجمن
				<a href="member-courses?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>"
				   data-toggle="tooltip" title="برگردید به درس"><?php echo htmlentities($current_course->name); ?></a>
			</h3>
			<fieldset>
				<legend></legend>
				<form class="form-horizontal submit-comment" action="add-comment.php" method="POST" role="form">
					<!--content-->
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
						<img class="img-circle pull-right hidden-sm" width="100" src="//www.gravatar.com/avatar/<?php echo md5($member->email); ?>?s=100&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->username; ?>">
					</label>
					<input type="hidden" name="course" value="<?php echo urlencode($current_course->id); ?>">
					<div class="controls">
							<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" rows="3" required
							          placeholder="سوال یا نظرتان را اینجا وارد کنید و کد ها را داخل تگ <pre> وارد کنید
برای چپ چین کردن از <'p class='edit> استفاده کنید
کلمات انگلیسی بین کلمات فارسی را داخل تگ <code> بیاندازید"></textarea>
					</div>
					<!--buttons-->
					<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
						<button class="btn btn-primary" name="submit" id="submit" type="submit">
							بفرست
						</button>
					</div>
				</form>
			</fieldset>
			<hr/>
			<div id="forum">
				<div id="ajax-comments">
					<?php include_layout_template('course-comments.php'); ?>
				</div>
			</div>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
			<h2>انجمن ها</h2>
			<?php echo courses($current_category, $current_course, TRUE); ?>
		</aside>
	</section>
<?php include_layout_template('footer.php'); ?>