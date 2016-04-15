<?php require_once('../../includes/initialize.php');
$filename = basename(__FILE__);
$session->confirm_admin_logged_in();
find_selected_course();
if( ! $current_course || ! $current_category) {
	redirect_to('author_courses.php');
}
$errors = '';
if(isset($_POST["submit"])) {
	global $database;
	$course          = Course::find_by_id($current_course->id, FALSE);
	$name            = ucfirst($_POST['course_name']);
	$category_id     = (int) $_POST['category_id'];
	$youtubePlaylist = $_POST['youtubePlaylist'];
	$file_link       = $_POST['file_link'];
	$position        = (int) $_POST['position'];
	$visible         = (int) $_POST['visible'];
	$content         = $_POST['content'];
	$sql             = "UPDATE courses SET ";
	$sql .= "category_id = " . $database->escape_value($category_id) . ", ";
	$sql .= "name = '" . $database->escape_value($name) . "', ";
	$sql .= "youtubePlaylist = '" . $database->escape_value($youtubePlaylist) . "', ";
	$sql .= "file_link = '" . $database->escape_value($file_link) . "', ";
	$sql .= "position = " . $database->escape_value($position) . ", ";
	$sql .= "visible = " . $database->escape_value($visible) . ", ";
	$sql .= "content = '" . $database->escape_value($content) . "' ";
	$sql .= "WHERE id = " . $database->escape_value($course->id);
	$database->query($sql);
	$result = $database->affected_rows();
	if($result == 1) {
		$session->message('درس بروزرسانی شد.');
		redirect_to("admin_courses.php?category=" . $current_category->id . "&course=" . $current_course->id);
	} else {
		$errors = 'درس بروزرسانی نشد!';
	}
} else {
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square"></i> ویرایش درس</h2>
			<form class="form-horizontal" action="edit_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id) ?>" method="POST" role="form">
				<fieldset>
					<!--name-->
					<legend><?php echo htmlentities(ucfirst($current_course->name)); ?>
					</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="course_name">
							اسم درس
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="course_name" id="course_name" autofocus placeholder="اسم درس" value="<?php echo htmlentities($current_course->name); ?>"/>
						</div>
					</section>
					<!--YouTube Playlist ID-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="youtubePlaylist">
							لیست پخش یوتیوب
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="youtubePlaylist" id="youtubePlaylist" placeholder="YouTube Playlist ID" maxlength="255" value="<?php echo htmlentities($current_course->youtubePlaylist); ?>"/>
						</div>
					</section>
					<!--Exercise File Link-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="file_link">
							لینک فایل تمرینی
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="file_link" id="file_link" placeholder="Exercise File Link" maxlength="255" value="<?php echo htmlentities($current_course->file_link); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<option value="" disabled>انتخاب کنید</option>
								<?php
								$page_set = Course::num_courses_for_category($current_course->category_id);
								for($count = 1; $count <= $page_set; $count++):
									echo "<option value='{$count}'";
									if($current_course->position == $count): echo " selected"; endif;
									echo ">{$count}</option>";
								endfor;
								?>
							</select>
						</div>
					</section>
					<!--subject-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="category_id">زیر موضوع</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="category_id" id="category_id">
								<?php
								foreach(Category::find_all(FALSE) as $category):
									echo "<option value='{$category->id}'";
									if($current_course->category_id == $category->id):
										echo "selected";
									endif;
									echo ">{$category->name}</option>";
								endforeach;
								?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نشر شد</label>
						<div class="controls radio-disabled">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" value="0"
										<?php if($current_course->visible == 0) echo 'checked'; ?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" value="1"
										<?php if($current_course->visible == 1) echo 'checked'; ?> > بله
							</label>
						</div>
					</section>
					<!--description-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="desc">توضیحات</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-8 col-md-8 col-lg-8" name="content" id="desc" cols="30" rows="10" placeholder="توضیحات" required><?php echo htmlentities($current_course->content); ?></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<a class="btn btn-danger" href="admin_courses.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>">لغو</a>
							<a class="btn btn-info confirmation" href="delete_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>">
								حذف
							</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								ویرایش
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
			<?php echo courses($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>