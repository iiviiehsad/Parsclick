<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
$yourself = Admin::find_by_id($session->id);
$admin    = Admin::find_by_id($_GET['id']);
$errors   = '';
if( ! $admin) {
	$session->message('مدیر پیدا نشد!');
	redirect_to('admin_list.php');
}
if(isset($_POST['submit'])) {
	$admin->id       = (int) $_GET['id'];
	$admin->username = strtolower($_POST['username']);
	if($yourself == $admin && ! empty($_POST['password'])) {
		// if this is true means this is your profile
		// and you can change your password
		$admin->password = $admin->password_encrypt($_POST['password']);
	}
	$admin->first_name = ucwords(strtolower($_POST['first_name']));
	$admin->last_name  = ucwords(strtolower($_POST['last_name']));
	$admin->email      = strtolower($_POST['email']);
	$result            = $admin->save();
	if($result) {
		$session->message('مدیر بروزرسانی شد.');
		redirect_to('admin_list.php');
	} else {
		$errors = 'نتوانستیم مدیر را بروزرسانی کنیم یا اینکه شما چیزی عوض نکردید.';
	}
} else {
}
include_layout_template('admin_header.php');
include('../_/components/php/admin_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-9 col-lg-9">
		<article>
			<h2><i class="fa fa-pencil-square-o"></i> ویرایش مدیر</h2>

			<form class="form-horizontal" action="edit_admin.php?id=<?php echo urlencode($admin->id); ?>" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-user"></i> <?php echo htmlentities(ucwords(strtolower($admin->full_name()))); ?>
					</legend>
					<!--username-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username" value="<?php echo htmlentities($admin->username); ?>"/>
						</div>
					</section>
					<!--password-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد</label>
						<div class="controls">
							<input <?php if($session->id != $admin->id): echo 'disabled'; endif; ?> class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="New Password"/>
						</div>
					</section>
					<!--first_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="نام" value="<?php echo htmlentities($admin->first_name); ?>"/>
						</div>
					</section>
					<!--last_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="نام خانوادگی" value="<?php echo htmlentities($admin->last_name); ?>"/>
						</div>
					</section>
					<!--email-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlentities($admin->email); ?>"/>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="admin_list.php">لغو</a>
							<?php if($yourself != $admin): ?>
								<a class="btn btn-info" href="delete_admin.php?id=<?php echo urlencode($admin->id); ?>" onclick="return confirm('آیا مطمئن هستید که می خواهید مدیر  <?php echo htmlentities(ucwords(strtolower($admin->full_name()))); ?> را حذف کنید؟');">
									حذف
								</a>
							<?php endif; ?>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								ویرایش
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-3 col-lg-3">
		<aside>
			<h2>عوض کردن پسورد</h2>
			<p>اسم شما <?php echo $yourself->full_name(); ?> است.</p>
			<?php
			if($yourself->id == $admin->id): ?>
				<p class='text-success'>شما قادر به عوض کردن پسورد هستید چون این اطلاعات خودتان است. لطفا هیچ چیزی را خالی
				                        نگذارید.</p>
			<?php else: ?>
				<p class='text-danger'>شما قادر به عوض کردن پسورد نیستید چون این اطلاعات شما نیست!</p>
			<?php endif; ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>