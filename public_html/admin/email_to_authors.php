<?php require_once('../../includes/initialize.php');
require_once('../../includes/vendor/autoload.php');
$session->confirm_admin_logged_in();
$title   = 'پارس کلیک - ایمیل به نویسندگان';
$errors  = '';
$message = '';
if (isset($_POST['submit'])) {
	$mail    = new Mail();
	$subject = trim($_POST['subject']);
	$data    = nl2br($_POST['important']);
	$content = nl2br($_POST['message']);
	$emails  = [];
	foreach (Author::find_all() as $author) {
		$emails[] = $author->email;
	}
	$result = $mail->sendEmailTo($emails, $data, $content, $subject);
	if ($result) {
		$message = 'پیام به همه نویسندگان فرستاده شد.';
	} else {
		$errors = 'خطا در فرستادن پیام!';
	}
}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('admin_nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>تماس با اعضا</h2>
			<form action="email_to_authors.php" method="POST" role="form" data-remote>
				<fieldset>
					<legend>لطفا از فرم زیر برای تماس با اعضا استفاده کنید</legend>
					<div class="form-group">
						<label for="name">موضوع</label>
						<input type="text" name="subject" class="form-control" id="name" placeholder="موضوع ایمیل" required
						       value=""/>
					</div>
					<div class="form-group">
						<label for="message">پیغام</label>
						<textarea class="form-control" name="message" id="message" placeholder="پیغام" required></textarea>
					</div>
					<div class="form-group">
						<label for="name">مطلب مهم</label>
						<textarea class="form-control" name="important" id="important"
						          placeholder="مطلب کوتاه و مهم: این مطلب هایلایت میشود پس باید کوتاه باشد"></textarea>
					</div>
					<br/>
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary"
						        data-loading-text="یک لحظه صبر کنید <i class='fa fa-spinner fa-pulse'></i>">
							بفرست
						</button>
					</div>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2><i class="fa fa-info-circle"></i> اطلاعات</h2>
			<div class="form-group">
				<label for="emails">ایمیل ها</label>
				<textarea class="form-control edit" name="emails" id="emails" placeholder="ایمیل ها"
				          disabled><?php foreach (Author::find_all() as $authors) echo $authors->email, ", \n"; ?></textarea>
			</div>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>