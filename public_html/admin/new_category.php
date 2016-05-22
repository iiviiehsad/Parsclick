<?php require_once('../../includes/initialize.php');
$filename = basename(__FILE__);
$session->confirm_admin_logged_in();
find_selected_course();
if(isset($_POST['submit'])) {
	$category           = new Category();
	$category->id       = (int) '';
	$category->name     = ucwords(strtolower($_POST['category_name']));
	$category->position = (int) $_POST['position'];
	$category->visible  = (int) $_POST['visible'];
	$result             = $category->create();
	if($result) { // Success
		$session->message('موضوع درست شد.');
		redirect_to('admin_courses.php');
	} else { // Failure
		$session->message('موضوع درست نشد!');
		redirect_to('new_category.php');
	}
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-list-alt"></i> ساخت موضوع جدید</h2>

			<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form">
				<fieldset id="login">
					<legend>جزئیات موضوع</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="category_name">اسم موضوع</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="category_name" id="category_name"
							       placeholder="اسم موضوع" value="" required/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<option disabled value="">انتخاب کنید</option>
								<?php for($count = 1; $count <= (Category::num_rows() + 1); $count++): ?>
									<option value='<?php echo $count; ?>'><?php echo $count; ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نمایان</label>
						<div class="controls">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" value="0"> خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" value="1"> بله
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="admin_courses.php">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">بساز
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