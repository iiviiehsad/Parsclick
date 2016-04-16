<?php require_once('../includes/initialize.php');
$filename = basename(__FILE__);
$title    = 'پارس کلیک - دروس';
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_category && $current_course): ?>
				<?php $json = get_playlist_content($current_course->youtubePlaylist); ?>
				<?php $playlist_set = Playlist::courses_playlist_for_member($current_course->id, $member->id); ?>
				<?php if( ! $playlist_set): ?>
					<form action="add-to-playlist" method="POST" class="addtoplaylist">
						<input type="hidden" name="course" value="<?php echo $current_course->id; ?>">
						<button id="btn" type="submit" class="btn btn-info" data-toggle="tooltip" title="اضافه به لیست پخش">
							<i class="fa fa-plus-circle"></i> اضافه به لیست
						</button>
					</form>
				<?php else: ?>
					&nbsp;
					<form action="remove-from-playlist" method="POST" class="removefromplaylist">
						<input type="hidden" name="playlist" value="<?php echo $playlist_set->id; ?>">
						<button id="btn" type="submit" class="btn btn-danger" data-toggle="tooltip" title="حذف از لیست پخش">
							<i class="fa fa-minus-circle"></i> حذف از لیست
						</button>
					</form>
					&nbsp;
				<?php endif; ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(empty($current_course->file_link) && File::num_files_for_course($current_course->id) == 0): ?>
					<a class="btn btn-danger disabled" href="#" disabled>
						فایلی موجود نیست
					</a>
				<?php endif; ?>
				<?php if( ! empty($current_course->file_link)): ?>
					<a class="btn btn-success" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="دانلود کنید">
						<i class="fa fa-files-o fa-lg"></i>&nbsp; دانلود فایل ها
					</a>
				<?php endif; ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(File::num_files_for_course($current_course->id) > 0): ?>
					<?php $files = File::find_files_for_course($current_course->id); ?>
					<?php foreach($files as $file): ?>
						<a class="btn btn-primary btn-small" href="<?php echo urlencode($file->file_path()); ?>">
							<?php echo htmlentities($file->description); ?>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php include_layout_template('course-info.php'); ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<a class="btn btn-primary" href="forum?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>" data-toggle="tooltip" data-placement="bottom" title="سوالات و نظرات"><i class="fa fa-comments fa-lg"></i>
					انجمن<?php echo "<span class='badge'>" . convert(Comment::count_comments_for_course($current_course->id)) . "</span>"; ?>
				</a>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php include_layout_template('list-videos.php'); ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
			<?php else: ?>
				<div class="hidden-sm"><?php include_layout_template('member_course_info.php'); ?></div>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="member-course-search" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="arial glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="30" maxlength="50" data-toggle="tooltip" data-placement="top" title="جستجو کنید و اینتر بزنید" placeholder="جستجوی درس"/>
				</div>
			</form>
			<h2>موضوعات و دروس</h2>
			<?php echo courses($current_category, $current_course, TRUE); ?>
		</aside>
	</section>
<?php include_layout_template('video-modal.php'); ?>
<?php include_layout_template('footer.php'); ?>