<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
find_selected_course();
if(!$current_category) {
	redirect_to("author_courses.php");
}
$errors = "";
if(isset($_POST['submit'])) {
	$author                  = Author::find_by_id($session->id);
	$course                  = new Course();
	$course->id              = (int)'';
	$course->category_id     = $current_category->id; //$_GET['category'];
	$course->author_id       = $author->id;
	$course->name            = $_POST["course_name"];
	$course->youtubePlaylist = $_POST["youtubePlaylist"];
	$course->file_link       = $_POST["file_link"];
	$course->position        = (int)$_POST["position"];
	if($author->id == 1) { $course->visible = (int)$_POST["visible"]; }
	$course->content         = $_POST["description"];
	$result                  = $course->create();
	if($result) { // Success
		$session->message("درس ساخته شد. درس قبل از نشر باید توسط مدیران بازبینی شود.");
		redirect_to("author_courses.php");
	} else { // Failure
		$errors = "درس ساخته نشد!";
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-film"></i> درس جدید</h2>

			<form class="form-horizontal" method="POST" role="form" action="new_course.php?category=<?php echo urlencode($current_category->id); ?>">
				<fieldset>
					<!--course name-->
					<legend><?php echo ucfirst($current_category->name); ?></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="course_name">
							اسم درس
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="course_name" id="course_name" placeholder="اسم درس" value="" required/>
						</div>
					</section>
					<!--YouTube playlist ID-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="youtubePlaylist">
							لیست پخش یوتیوب
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="youtubePlaylist" id="youtubePlaylist" placeholder="YouTube Playlist ID" value=""/>
						</div>
					</section>
					<!--Exercise File Link-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="file_link">
							لینک فایل تمرینی
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="file_link" id="file_link" placeholder="Exercise File Link" value=""/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<?php $page_count = Course::num_courses_for_category($current_category->id);
								echo "<option selected value=" . ++$page_count . ">" . $page_count . "</option>";
								?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نشر شد</label>
						<div class="controls radio-disabled">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" <?php echo $author->id == 1 ? ' value="0" ' : ' disabled'; ?> >
								خیر
							</label>
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioYes" <?php echo $author->id == 1 ? ' value="1" ' : ' disabled'; ?> >
								بله
							</label>
						</div>
					</section>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="desc">توضیحات</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-8 col-md-8 col-lg-8" name="description" id="desc" cols="30" rows="10" placeholder="توضیحات" required></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<a class="btn btn-danger" href="author_courses.php">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								بساز
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و دروس</h2>
			<?php echo author_courses($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>