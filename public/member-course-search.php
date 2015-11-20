<?php require_once("../includes/initialize.php"); ?>
<?php $session->confirm_logged_in(); ?>
<?php $filename = basename(__FILE__);
$title = "پارس کلیک - جستجوی دروس";
$member         = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
if(isset($_GET["q"]) && !empty($_GET["q"]) && $_GET["q"] != " ") {
	$course_set = Course::search($_GET["q"]);
} else { // this is a $_GET request
	$session->message("شما چیزی جستجو نکردید.");
	redirect_to("member-courses");
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>نتیجه جستجو</h2>
			<?php if(!empty($course_set)) { ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
						<tr>
							<th>درس های پیدا شده: <span class="badge"><?php echo count($course_set); ?></span></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($course_set as $course): ?>
							<tr>
								<td>
									<a href="member-courses?category=<?php echo urldecode($course->category_id); ?>&course=<?php echo urldecode($course->id); ?>">
										<?php echo htmlentities($course->name); ?>
									&nbsp;	توسط <?php echo htmlentities(Author::find_by_id($course->author_id)->full_name()); ?>
									</a>
									<p>
										<small><?php echo truncate(nl2br(htmlentities($course->content)), 200) ?></small>
									</p>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php } else {
				$session->message("نتیجه ای پیدا نشد. لطفا دوباره بگردید.");
				redirect_to("member-courses");
			} ?>
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
			<?php echo member_courses($current_category, $current_course); ?>
		</aside>
	</section>

<?php include_layout_template("footer.php"); ?>