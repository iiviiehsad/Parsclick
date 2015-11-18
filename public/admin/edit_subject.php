<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_admin_logged_in();
find_selected_article();
$errors = "";
if(!$current_subject) {
	redirect_to("admin_articles.php");
}
if(isset($_POST['submit'])) {
	$subject           = Subject::find_by_id($current_subject->id, FALSE);
	$subject->name     = ucwords(strtolower($_POST["subject_name"]));
	$subject->position = (int)$_POST["position"];
	$subject->visible  = (int)$_POST["visible"];
	$result            = $subject->save();
	if($result) { // Success
		$session->message("موضوع بروزرسانی شد.");
		redirect_to("edit_subject.php?subject=" . $current_subject->id);
	} else { // Failure
		$errors = "موضوع بروزرسانی نشد!";
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square-o"></i> ویرایش موضوع</h2>

			<form class="form-horizontal" action="edit_subject.php?subject=<?php echo urlencode($current_subject->id); ?>" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-list-alt"></i> <?php echo htmlentities(ucfirst($current_subject->name)); ?></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="subject_name">اسم موضوع</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="subject_name" id="subject_name" autofocus placeholder="اسم موضوع" value="<?php echo htmlentities($current_subject->name); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8" name="position" id="position">
								<option disabled value="">انتخاب کنید</option>
								<?php
								for($count = 1; $count <= Subject::num_rows(); $count++):
									echo "<option value=\"{$count}\"";
									if($current_subject->position == $count) {
										echo " selected";
									}
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
										<?php if($current_subject->visible == 0) { echo "checked"; } ?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" value="1"
										<?php if($current_subject->visible == 1) { echo "checked"; } ?> > بله
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="admin_articles.php?subject=<?php echo urldecode($current_subject->id); ?>">لغو</a>
							<a class="btn btn-info" href="delete_subject.php?subject=<?php echo urlencode($current_subject->id); ?>" onclick="return confirm('Are you sure?');">
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
			<h2>موضوعات و مقالات</h2>
			<?php echo admin_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>