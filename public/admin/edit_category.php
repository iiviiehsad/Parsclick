<?php require_once("../../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $session->confirm_admin_logged_in(); ?>
<?php find_selected_course();
if(!$current_category) {
	redirect_to("admin_courses.php");
}
if(isset($_POST['submit'])) {
	$category           = Category::find_by_id($current_category->id, FALSE);
	$category->name     = ucwords(strtolower($_POST["category_name"]));
	$category->position = (int)$_POST["position"];
	$category->visible  = (int)$_POST["visible"];
	$result             = $category->save();
	if($result) {
		$session->message("موضوع بروزرسانی شد.");
		redirect_to("edit_category.php?category=" . $current_category->id);
	} else {
		$errors = "موضوع بروزرسانی نشد!";
	}
} else {
	$errors = "";
} ?>
<?php include_layout_template("admin_header.php"); ?>
<?php include("../_/components/php/admin_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square-o"></i> ویرایش موضوع</h2>

			<form class="form-horizontal" action="edit_category.php?category=<?php echo urlencode($current_category->id); ?>" method="post" role="form">
				<fieldset id="login">
					<legend><i class="fa fa-list-alt"></i> <?php echo htmlentities(ucfirst($current_category->name)); ?></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="category_name">اسم موضوع</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="category_name" id="category_name" autofocus placeholder="اسم موضوع" value="<?php echo htmlentities($current_category->name); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8" name="position" id="position">
								<option disabled value="">انتخاب کنید</option>
								<?php for($count = 1; $count <= Category::num_rows(); $count++):
									echo "<option value=\"{$count}\"";
									if($current_category->position == $count) {
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
									<?php if($current_category->visible == 0) {
										echo "checked";
									} ?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" value="1"
									<?php if($current_category->visible == 1) {
										echo "checked";
									} ?> > بله
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="admin_courses.php?category=<?php echo urldecode($current_category->id); ?>">لغو</a>
							<a class="btn btn-info" href="delete_category.php?category=<?php echo urlencode($current_category->id); ?>" onclick="return confirm('آیا مطمئن هستید؟');">
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
			<?php echo admin_courses($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>