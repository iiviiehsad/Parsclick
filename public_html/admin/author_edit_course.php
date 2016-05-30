<?php require_once('../../includes/initialize.php');
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
find_selected_course();
$errors = '';
if ( ! $current_course || ! $current_category) {
	redirect_to('author_courses.php');
	# check to see the course belong to this author in order to edit
} elseif ( ! check_ownership($current_course->author_id, $session->id)) {
	$session->message('شما قادر به تغییر این درس نیستید');
	redirect_to('author_courses.php');
}
if (isset($_POST['submit'])) {
	$current_course->category_id     = $current_category->id;
	$current_course->name            = $_POST['course_name'];
	$current_course->youtubePlaylist = $_POST['youtubePlaylist'];
	$current_course->file_link       = $_POST['file_link'];
	$current_course->visible         = $_POST['visible'];
	$current_course->content         = $_POST['description'];
	$result                          = $current_course->save();
	if ($result) {
		$session->message('درس بروزرسانی شد.');
		redirect_to('author_courses.php?category=' . $current_category->id . '&course=' . $current_course->id);
	} else {
		$errors = 'درس بروزرسانی نشد یا چیزی تغییر نیافت!';
	}
}
include_layout_template('admin_header.php');
include_layout_template('author_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square"></i> ویرایش درس</h2>

			<form class="form-horizontal"
			      action="author_edit_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id) ?>"
			      method="post" role="form" data-remote>
				<fieldset>
					<legend><?php echo htmlentities(ucfirst($current_course->name)); ?>
					</legend>
					<!--name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="course_name">
							اسم درس
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="course_name" id="course_name"
							       placeholder="اسم درس" maxlength="255" value="<?php echo htmlentities($current_course->name); ?>"/>
						</div>
					</section>
					<!--YouTube Playlist ID-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="youtubePlaylist">
							لیست پخش یوتیوب
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="youtubePlaylist"
							       id="youtubePlaylist" placeholder="YouTube Playlist ID" maxlength="255"
							       value="<?php echo htmlentities($current_course->youtubePlaylist); ?>"/>
						</div>
					</section>
					<!--Exercise File Link-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="file_link">
							لینک فایل تمرینی
						</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="file_link" id="file_link"
							       placeholder="Exercise File Link" maxlength="255"
							       value="<?php echo htmlentities($current_course->file_link); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position"
							        disabled>
								<?php echo '<option value="' . $current_course->position . '" selected>' . $current_course->position . '</option>'; ?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نشر شد</label>
						<div class="controls radio-disabled">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible"
								       id="inlineRadioNo" <?php echo $author->id == 1 ? ' value="0" ' : ' disabled '; ?>
										<?php echo $current_course->visible == 0 ? 'checked' : ''; ?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible"
								       id="inlineRadioYes" <?php echo $author->id == 1 ? ' value="1" ' : ' disabled '; ?>
										<?php echo $current_course->visible == 1 ? 'checked' : ''; ?> > بله
							</label>
						</div>
					</section>
					<!--description-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="desc">توضیحات</label>
						<div class="controls">
							<textarea class="yekan col-xs-12 col-sm-8 col-md-8 col-lg-8" name="description" id="desc" cols="30"
							          rows="10" placeholder="Description"
							          required><?php echo htmlentities($current_course->content); ?></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<a class="btn btn-danger"
							   href="author_courses.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>">لغو</a>
							<a class="btn btn-info confirmation"
							   href="author_delete_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>">
								حذف
							</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit"
							        data-loading-text="یک لحظه صبر کنید <i class='fa fa-spinner fa-pulse'></i>">
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