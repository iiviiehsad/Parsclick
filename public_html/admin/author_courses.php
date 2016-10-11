<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
find_selected_course();
include_layout_template('admin_header.php');
$file_max_file_size = File::$max_file_size; // 32MB
$errors             = '';
if (isset($_POST['submit_file'])) {
	$file              = new File();
	$file->id          = (int) '';
	$file->course_id   = (int) $current_course->id;
	$file->description = $_POST['description'];
	$file->attach_file($_FILES['single_file']);
	if ($file->save()) {
		$session->message("فایل {$file->description} با موفقیت آپلود شد.");
		redirect_to('author_courses.php?category=' . urlencode($current_category->id) . '&course=' . urlencode($current_course->id));
	} else {
		$errors = implode(' ', $file->errors);
	}
}
include_layout_template('author_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php
			if ($current_category && $current_course): ?>
				<?php $json = get_playlist_content($current_course->youtubePlaylist); ?>
				<?php if (check_ownership($current_course->author_id, $session->id)): ?>
					<a class="btn btn-primary"
					   href="author_edit_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>"
					   title="ویرایش">
						ویرایش
					</a>
				<?php endif; ?>
				<?php include_layout_template('course-info.php'); ?>
				<!-- -------------------------------------------FILE LINK--------------------------------------------- -->
				<?php if ( ! empty($current_course->file_link)): ?>
					<a class="btn btn-primary" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank"
					   title="لینک فایل تمرینی">
						لینک فایل تمرینی
					</a>
				<?php endif; ?>
				<!-- -------------------------------------------FILES------------------------------------------------- -->
				<?php if (File::num_files_for_course($current_course->id) > 0): ?>
					<?php $files = File::find_files_for_course($current_course->id); ?>
					<?php foreach ($files as $file): ?>
						<?php if (check_ownership($current_course->author_id, $session->id)): ?>
							<div class="btn-group">
								<a class="btn btn-primary btn-small" href="../files/<?php echo urlencode($file->name); ?>">
									<?php echo htmlentities($file->description); ?>
								</a>
								<a class="btn btn-danger btn-small confirmation"
								   href="author_delete_file.php?id=<?php echo urlencode($file->id); ?>">
									<i class="fa fa-trash fa-lg"></i>
								</a>
							</div>
						<?php else: ?>
							<a class="btn btn-small btn-success" href="../<?php echo urlencode($file->name); ?>">
								<?php echo htmlentities($file->description); ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<!-- --------------------------------Check to see if there is any file-------------------------------- -->
				<?php if (File::num_files_for_course($current_course->id) == 0): ?>
					<?php if (check_ownership($current_course->author_id, $session->id)): ?>
						<div class="alert alert-info">
							<h3><span class="label label-as-badge label-info"><i class="fa fa-upload fa-lg"></i> آپلود فایل تمرینی زیپ</span>
								<small><?php echo check_size($file_max_file_size); ?></small>
							</h3>
							<form enctype="multipart/form-data" data-remote
							      action="author_courses.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>"
							      method="POST" class="form-horizontal fileForm" role="form">
								<label style="cursor:pointer;" class="control-label btn btn-small btn-primary" for="single_file">
									برای انتخاب فایل اینجا را کلیک کنید
								</label>
								<div class="controls">
									<input name="MAX_FILE_SIZE" value="<?php echo $file_max_file_size; ?>" type="hidden"/>
									<input type="file" name="single_file" class="form-control" id="single_file" accept="application/zip"/>
								</div>
								<section class="row">
									<div class="input-group col-xs-11 col-sm-11 col-md-11 col-lg-11">
										<input type="text" name="description" class="form-control input-small" placeholder="اسم فایل "
										       maxlength="255" required/>
										<span class="input-group-btn">
											<button class="btn btn-primary btn-small" type="submit" name="submit_file"
											        data-loading-text="در حال آپلود <i class='fa fa-spinner fa-pulse'></i>">
												آپلود
											</button>
										</span>
									</div>
								</section>
							</form>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<!-- ------------------------------------------VIDEOS------------------------------------------------- -->
				<?php include_layout_template('list-videos.php'); ?>
				<!--------------------------------------------COMMENTS--------------------------------------------------->
				<article id="comments">
					<?php include_layout_template('course-comments.php'); ?>
				</article>
			<?php elseif ($current_category): ?>
				<?php if ( ! $current_category->visible) redirect_to('author_courses.php'); ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title">
							<a class="btn btn-danger btn-small"
							   href="new_course.php?category=<?php echo urlencode($current_category->id); ?>" data-toggle="tooltip"
							   title="درس جدید">
								<i class="fa fa-plus fa-lg"></i>
							</a>
							<?php echo htmlentities(ucwords($current_category->name)); ?>
						</h3>
					</div>
					<div class="panel-body">
						<?php include_layout_template('courses-under-category.php'); ?>
					</div>
				</div>
			<?php else: ?>
				<article>
					<h2>لطفا یک موضوع یا درس انتخاب کنید.</h2>
					<p class="lead text-danger"><i class="fa fa-info-circle"></i> نکات مهم:</p>
					<ul>
						<li><p>به عنوان یک نویسنده شما مسئول ساختن، بروزرساندن، و پاک کردن درس های خود هستید.</p></li>
						<li><p>درس هایی که توسط شما نوشته می شوند، توسط مدیران ویرایش و تنظیم خواهند شد.</p></li>
						<li><p>در کنار اسم هر درس، آیکانی به شکل چشم وجود دارد که نشاندهنده ی این است که درس نشر شده یا
						       نشده است. درس هایی که توسط شما ساخته می شوند، تا زمان تنظیم و ویرایش آنها توسط مدیران
						       قابل
						       دیدن نمی باشند.</p></li>
						<li><p>لطفا سعی بر پاک کردن درس هایی که از قبل توسط مدیران تنظیم شده اند ننمائید مگر اینکه مایل
						       به
						       بروزرساندن آنها هستید.</p></li>
						<li><p>پاک کردن درسی بدون دلیل باعث معلق شدن عضویت شما به عنوان نویسنده خواهد شد.</p></li>
						<li><p>هنگام اضافه کردن درس شناسه لیست پخش یوتیوب فراموشتان نشود.</p></li>
						<li><p>اگر کانال یوتیوب دارید، لطفا به
								<a href="https://developers.google.com/youtube/v3/getting-started" target="_blank"
								   title="YouTube Data API Overview">
									این آدرس</a> روید و مدیر وبسایت را از
								<a href="https://developers.google.com/youtube/v3/getting-started">YouTube API Key</a>
						       با خبر کنید. این کلید به کاربران اجازه می دهد که با سرعت بیشتری به لیست پخش یوتیوب شما
						       دسترسی پیدا کنند.</p></li>
					</ul>
				</article>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و دروس</h2>
			<?php echo courses($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template('video-modal.php'); ?>
<?php include_layout_template('admin_footer.php'); ?>