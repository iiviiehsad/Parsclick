<?php require_once('../includes/initialize.php'); ?>
<?php $session->confirm_logged_in();
$title  = 'پارس کلیک - جستجوی دروس';
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$search_query = trim($_GET['q']);
if (isset($search_query) && ! empty($search_query)) {
	$course_set = Course::search($search_query);
} else { // this is a $_GET request
	$session->message('شما چیزی جستجو نکردید.');
	redirect_to('member-courses');
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if ( ! empty($course_set)): ?>
				<h2>نتیجه جستجو برای <?php echo $search_query; ?></h2>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>درس های پیدا شده: <span class="badge"><?php echo convert(count($course_set)); ?></span></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($course_set as $course): ?>
								<tr>
									<td>
										<strong>
											<i>
												<a href="member-courses?category=<?php echo urlencode($course->category_id); ?>&course=<?php echo urlencode($course->id); ?>">
													<?php echo htmlentities($course->name); ?>
													<small class="badge">&nbsp;توسط <?php echo htmlentities(Author::find_by_id($course->author_id)->full_name()); ?></small>
												</a>
											</i>
										</strong>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="center">
					<h3>برای <?php echo $search_query; ?></h3>
					<h1>چیزی پیدا نشد!</h1>
					<h1><i class="fa fa-frown-o fa-5x"></i></h1>
				</div>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="member-course-search" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی دروس"/>
				</div>
			</form>
			<h2>موضوعات</h2>
			<?php echo courses($current_category, $current_course, TRUE); ?>
		</aside>
	</section>

<?php include_layout_template('footer.php'); ?>