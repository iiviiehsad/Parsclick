<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_admin_logged_in();
find_selected_article();
if(isset($_POST['submit'])) {
	$subject           = new Subject();
	$subject->id       = (int)'';
	$subject->name     = ucwords(strtolower($_POST["subject_name"]));
	$subject->position = (int)$_POST["position"];
	$subject->visible  = (int)$_POST["visible"];
	$result            = $subject->create();
	if($result) { // Success
		$session->message("موضوع درست شد");
		redirect_to("admin_articles.php");
	} else { // Failure
		$session->message("موضوع درست نشد!");
		redirect_to("new_subject.php");
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message); ?>
	<section class="main col-xs-12 col-sm-8 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-list-alt"></i> ساخت موضوع جدید </h2>

			<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" role="form">
				<fieldset id="login">
					<legend>جزئیات موضوع</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="subject_name">اسم موضوع
						                                                                                     &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="subject_name" id="subject_name" autofocus placeholder="اسم موضوع" value="" required/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل &nbsp;</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<option disabled value="">--Please Select--</option>
								<?php for($count = 1; $count <= (Subject::num_rows() + 1); $count++): ?>
									<option value='<?php echo $count; ?>'><?php echo $count; ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نمایان &nbsp;</label>
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
							<a class="btn btn-danger" href="admin_articles.php">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">بساز
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>

	<section class="sidebar col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و مقالات</h2>
			<?php echo admin_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>