<?php require_once("../includes/initialize.php"); ?>
<?php if($session->is_logged_in()) {redirect_to("member.php");} ?>
<?php $title = "پارس کلیک - درس ها و ویدئو ها"; ?>
<?php $filename = basename(__FILE__); ?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/nav.php"); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<?php find_selected_course(); ?>
		<?php echo public_courses(); ?>
	</article>
</section><!-- main -->
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<h2>به اشتراک بگذارید</h2>
		<?php include "_/components/php/aside-share.php"; ?>
	</aside>
	<?php include("_/components/php/aside-watch.php"); ?>
	<?php include("_/components/php/aside-courses.php"); ?>
</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>
