<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
find_selected_article();
$errors = '';
if ( ! $current_subject) redirect_to('admin_articles.php');
if (isset($_POST['submit'])) {
	$subject           = Subject::find_by_id($current_subject->id, FALSE);
	$subject->name     = ucwords(strtolower($_POST['subject_name']));
	$subject->position = (int) $_POST['position'];
	$subject->visible  = (int) $_POST['visible'];
	$result            = $subject->save();
	if ($result) {
		$session->message('موضوع بروزرسانی شد.');
		redirect_to('edit_subject.php?subject=' . $current_subject->id);
	} else {
		$errors = 'موضوع بروزرسانی نشد!';
	}
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square-o"></i> ویرایش موضوع</h2>

			<form class="form-horizontal" action="edit_subject.php?subject=<?php echo urlencode($current_subject->id); ?>"
			      method="post" role="form" data-remote>
				<fieldset>
					<legend><?php echo htmlentities(ucfirst($current_subject->name)); ?></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="subject_name">اسم موضوع</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="subject_name" id="subject_name"
							       autofocus placeholder="اسم موضوع" value="<?php echo htmlentities($current_subject->name); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<option disabled value="">انتخاب کنید</option>
								<?php
								for ($count = 1; $count <= Subject::num_rows(); $count++):
									echo "<option value='{$count}'";
									if ($current_subject->position == $count): echo ' selected'; endif;
									echo ">{$count}</option>";
								endfor; ?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نمایان</label>
						<div class="controls">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" value="0"
										<?php echo ! $current_subject->visible ? 'checked' : ''; ?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" value="1"
										<?php echo $current_subject->visible ? 'checked' : ''; ?> > بله
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger"
							   href="admin_articles.php?subject=<?php echo urlencode($current_subject->id); ?>">لغو</a>
							<a class="btn btn-info confirmation"
							   href="delete_subject.php?subject=<?php echo urlencode($current_subject->id); ?>">
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
			<h2>موضوعات و مقالات</h2>
			<?php echo articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>