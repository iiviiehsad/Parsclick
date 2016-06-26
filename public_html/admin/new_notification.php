<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$admin  = Admin::find_by_id($session->id);
$errors = '';
if (isset($_POST['submit'])) {
	if ( ! has_presence($_POST['content'])) {
		$errors = 'محتوا خالی است!';
	} else {
		global $database;
		$notification           = new Notification();
		$notification->id       = $database->insert_id();
		$notification->admin_id = (int) $admin->id;
		$notification->content  = trim($_POST['content']);
		if ( ! empty($_POST['button_text']) && ! empty($_POST['button_url'])) {
			$notification->button_text = trim($_POST['button_text']);
			$notification->button_url  = trim($_POST['button_url']);
		}
		$notification->created = strftime('%Y-%m-%d %H:%M:%S', time());
		if ($notification->create()) {
			$session->message('اعلان ساخته شد.');
			redirect_to('admin_notifications.php');
		} else {
			$errors = 'اعلان ساخته نشد!';
		}
	}
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-bell-o"></i> اعلان جدید</h2>
			<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>"
			      method="POST" role="form" data-remote>
				<fieldset>
					<legend></legend>
					<!--content-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="content">محتوا</label>
						<div class="controls">
							<textarea dir="auto" class="col-xs-12 col-sm-8 col-md-8 col-lg-8" name="content" id="content" rows="10"
							          required placeholder="محتوای اعلان را اینجا تایپ کنید"></textarea>
						</div>
					</section>
					<!--button text-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="button_text">متن دگمه</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="button_text" id="button_text"
							       autofocus placeholder="متن دگمه  ( اختیاری )"/>
						</div>
					</section>
					<!--button url-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="button_url">لینک دگمه</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="button_url" id="button_url"
							       autofocus placeholder="لینک دگمه  ( اختیاری )"/>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<button class="btn btn-success" name="submit" id="submit" type="submit"
							        data-loading-text="در حال ساخت <i class='fa fa-spinner fa-pulse'></i>">
								بساز
							</button>
							<a class="btn btn-danger" href="admin_notifications.php">لغو</a>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<!--TODO-->
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>