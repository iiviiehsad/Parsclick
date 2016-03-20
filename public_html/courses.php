<?php
require_once("../includes/initialize.php");
if($session->is_logged_in()) {
	redirect_to("member.php");
}
$title    = "پارس کلیک - درس ها و ویدئو ها";
$filename = basename(__FILE__);
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/nav.php"); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<blockquote>
			<i class="fa fa-film fa-5x text-warning hidden-sm pull-left" style="font-size: 700%;"></i>
			<?php echo public_courses(); ?>
		</blockquote>
	</article>
</section><!-- main -->
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<?php include("_/components/php/aside-share.php"); ?>
	<?php include("_/components/php/aside-watch.php"); ?>
	<?php include("_/components/php/aside-courses.php"); ?>
</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>
