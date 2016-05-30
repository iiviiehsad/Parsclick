<?php require_once('../includes/initialize.php');
if ($session->is_logged_in()) redirect_to('member-courses');
$title = 'پارس کلیک - درس ها و ویدئو ها'; ?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<?php echo public_courses(); ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<?php include_layout_template('aside-share.php'); ?>
	<?php include_layout_template('aside-watch.php'); ?>
	<?php include_layout_template('aside-courses.php'); ?>
</section>
<?php include_layout_template('footer.php'); ?>
