<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_admin_logged_in();
if(isset($_POST['submit'])) {
	$author             = new Author();
	$author->id         = (int)"";
	$author->username   = trim(strtolower($_POST["username"]));
	$author->password   = $author->password_encrypt($_POST["password"]);
	$author->first_name = trim(ucwords(strtolower($_POST["first_name"])));
	$author->last_name  = trim(ucwords(strtolower($_POST["last_name"])));
	$author->email      = trim(strtolower($_POST["email"]));
	$author->status     = (int)$_POST["status"];
	$author->token      = md5(uniqid(rand()));
	$result             = $author->create();
	$send_email         = $author->email_confirmation_details($author->username);
	if($result && $send_email) { // Success
		$session->message("نویسنده با اسم کاربری " . strtoupper($author->username) . " ساخته شد.");
		redirect_to("author_list.php");
	} else { // Failure
		$session->message("نویسنده ساخته نشد!");
		redirect_to("author_list.php");
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-plus-square"></i> نویسنده جدید</h2>

			<form class="form-horizontal" action="new_author.php" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-user"></i> نویسنده جدید درست کنید</legend>
					<!--username-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">* اسم کاربری</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" placeholder="Username" required/>
						</div>
					</section>
					<!--password-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">* پسورد</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="Password" required/>
						</div>
					</section>
					<!--first_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name" placeholder="نام"/>
						</div>
					</section>
					<!--last_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name" placeholder="نام خانوادگی"/>
						</div>
					</section>
					<!--email-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="email" name="email" id="email" placeholder="Email"/>
						</div>
					</section>
					<!--status-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="status">* فعال</label>
						<div class="controls">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="status" id="inlineRadioNo" value="1" required/> بله
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="0" required/> خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="2" required/> مسدود
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="author_list.php">لغو</a>
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