<?php require_once('../includes/initialize.php');
if ($session->is_logged_in()) redirect_to('forum');
find_selected_course(TRUE);
$newest_course = Course::find_newest_course(TRUE);
if ( ! $current_course) $current_course = $newest_course;
$title       = 'پارس کلیک - انجمن ' . $current_course->name;
$description = 'پارس کلیک - انجمن ' . $current_course->name;
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article id="comments">
			<div class="panel panel-primary">
				<h3><i class="fa fa-comments fa-lg"></i>
					انجمن
					<?php echo htmlentities($current_course->name); ?>
					<span class="badge">
						<?php echo convert(count($current_course->comments())); ?> دیدگاه
					</span>
				</h3>
				<h4 class="label label-as-badge label-danger">برای شرکت در انجمن لطفا عضو شوید</h4>
			</div>
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