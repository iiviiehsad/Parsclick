<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
find_selected_course();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_category && $current_course): ?>
				<?php include_layout_template('course-info.php'); ?>
				<!-----------------------------------------------EDIT------------------------------------------>
				<a class="btn btn-primary btn-small" href="edit_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>" data-toggle="tooltip" title="ویرایش درس">
					ویرایش
				</a>
				&nbsp;
				<!---------------------------------------------FILE LINK--------------------------------------->
				<?php if( ! empty($current_course->file_link)): ?>
					<a class="btn btn-primary btn-small" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank" data-toggle="tooltip" title="لینک فایل تمرینی">
						لینک فایل تمرینی
					</a>
				<?php endif; ?>
				<h4>فایل های تمرینی:</h4>
				<?php if(File::num_files_for_course($current_course->id) != 0): ?>
					<?php $files = File::find_files_for_course($current_course->id); ?>
					<?php foreach($files as $file): ?>
						<div class="btn-group">
							<a class="btn btn-primary btn-small" href="../files/<?php echo urlencode($file->name); ?>">
								<?php echo htmlentities($file->name); ?>
							</a>
							<a class="btn btn-danger btn-small confirmation" href="delete_file.php?id=<?php echo urlencode($file->id); ?>">
								<i class="fa fa-trash fa-lg"></i>
							</a>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					فایل تمرینی نداریم
				<?php endif; ?>
				<!--------------------------------------------VIDEOS--------------------------------------------------->
				<?php include_layout_template('list-videos.php'); ?>
				<!--------------------------------------------COMMENTS--------------------------------------------------->
				<?php echo output_message($message); ?>
				<article id="comments">
					<?php include_layout_template('course-comments.php'); ?>
				</article>
			<?php elseif($current_category): ?>
			<div class="panel panel-danger">
				<div class="panel-heading">
					<h2><i class="fa fa-list-alt"></i>&nbsp;تنظیم موضوع</h2>
				</div>
				<dl class="dl-horizontal">
					<dt>اسم موضوع:</dt>
					<dd><?php echo htmlentities(ucwords($current_category->name)); ?></dd>
					<dt>محل:</dt>
					<dd><?php echo $current_category->position; ?></dd>
					<dt>نمایان:</dt>
					<dd><?php echo $current_category->visible == 1 ? 'بله' : 'خیر'; ?></dd>
					<dt>&nbsp;</dt>
					<dd>
						<a title="ویرایش" class="btn btn-primary btn-small" href="edit_category.php?category=<?php echo urlencode($current_category->id); ?>" data-toggle="tooltip">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</dd>
				</dl>
				<hr/>
				<div class="panel-body">
					<?php include_layout_template('courses-under-category.php'); ?>
				</div>
				<?php else: ?>
					<h2>لطفا یک درس یا یک موضوع انتخاب کنید.</h2>
				<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<a class="btn btn-success pull-left arial" href="new_category.php" data-toggle="tooltip" title="موضوع جدید اضافه کنید"><span class="glyphicon glyphicon-plus"></span></a>
			<h2>موضوعات و دروس</h2>
			<?php echo courses($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template('video-modal.php'); ?>
<?php include_layout_template('admin_footer.php'); ?>