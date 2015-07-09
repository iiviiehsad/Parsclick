<?php require_once("../../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $session->confirm_admin_logged_in(); ?>
<?php
if(isset($_POST['submit'])) {
	$admin             = new Admin();
	$admin->id         = (int)"";
	$admin->username   = trim(strtolower($_POST["username"]));
	$admin->password   = $admin->password_encrypt($_POST["password"]);
	$admin->first_name = trim(ucwords(strtolower($_POST["first_name"])));
	$admin->last_name  = trim(ucwords(strtolower($_POST["last_name"])));
	$admin->email      = trim(strtolower($_POST["email"]));
	$result            = $admin->create();
	if($result) { // Success
		$session->message("مدیر با اسم کاربری " . strtoupper($admin->username) . " ساخته شد.");
		redirect_to("admin_list.php");
	} else { // Failure
		$session->message("مدیر ساخته نشد!");
		redirect_to("admin_list.php");
	}
} else {
} ?>
<?php include_layout_template("admin_header.php"); ?>
<?php include("../_/components/php/admin_nav.php"); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-plus-square"></i> مدیر جدید</h2>

			<form class="form-horizontal" action="new_admin.php" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-user"></i> مدیر جدید درست کنید</legend>
					<!--username-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 arial" type="text" name="username" id="username" placeholder="Username" required/>
						</div>
					</section>
					<!--password-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="password" name="password" id="password" placeholder="Password" required/>
						</div>
					</section>
					<!--first_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="First Name"/>
						</div>
					</section>
					<!--last_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="Last Name"/>
						</div>
					</section>
					<!--email-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 arial" type="email" name="email" id="email" placeholder="Email"/>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="admin_list.php">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								بساز
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>