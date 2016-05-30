<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$errors = '';
if (isset($_POST['submit'])) {
	if (Member::find_by_username(trim($_POST['username']))) {
		$errors = 'اسم کاربری موجود نیست! لطفا از اسم کاربری دیگری استفاده کنید.';
	} elseif (Member::find_by_email(trim($_POST['email']))) {
		$errors = 'این ایمیل قبلا ثبت شده!';
	} else {
		$member             = new Member();
		$member->id         = (int) '';
		$member->username   = trim($_POST['username']);
		$member->password   = $member->password_encrypt($_POST['password']);
		$member->first_name = trim(ucwords(strtolower($_POST['first_name'])));
		$member->last_name  = trim(ucwords(strtolower($_POST['last_name'])));
		$member->gender     = trim($_POST['gender']);
		$member->address    = trim(ucwords(strtolower($_POST['address'])));
		$member->city       = trim(ucwords(strtolower($_POST['city'])));
		$member->email      = trim(strtolower($_POST['email']));
		$member->status     = (int) $_POST['status'];
		$member->token      = NULL;
		if ($member->create()) {
			$session->message('Member with the username ' . strtoupper($member->username) . ' was created.');
			redirect_to('member_list.php');
		} else {
			$errors = 'Member creation failed.';
		}
	}
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-xs-12 col-sm-8 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-plus-square"></i> عضو جدید </h2>

			<form class="form-horizontal" action="new_member.php" method="post" role="form" data-remote>
				<fieldset>
					<legend><i class="fa fa-user"></i> عضو جدید بسازید</legend>
					<!--username-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 arial" type="text" name="username" id="username"
							       placeholder="Username in English" required/>
						</div>
					</section>
					<!--password-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="password" name="password" id="password"
							       placeholder="پسورد" required/>
						</div>
					</section>
					<!--first_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="first_name" id="first_name"
							       placeholder="نام"/>
						</div>
					</section>
					<!--last_name-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی&nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="last_name" id="last_name"
							       placeholder="نام خانوادگی"/>
						</div>
					</section>
					<!--gender-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="gender">جنس &nbsp;</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="gender" id="gender">
								<option disabled selected value="">انتخاب کنید</option>
								<option value="مرد">مرد</option>
								<option value="زن">زن</option>
							</select>
						</div>
					</section>
					<!--address-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="address">کشور &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="address" id="address"
							       placeholder="کشور"/>
						</div>
					</section>
					<!--city-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="city">شهر &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="city" id="address"
							       placeholder="شهر"/>
						</div>
					</section>
					<!--email-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل &nbsp;</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 arial" type="text" name="email" id="post_code"
							       placeholder="Email"/>
						</div>
					</section>
					<!--status-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="status">فعال &nbsp;</label>
						<div class="controls">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="status" id="inlineRadioNo" value="0" required/> خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="status" id="inlineRadioYes" value="1" required/> بله
							</label>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit">&nbsp;</label>
						<div class="controls">
							<a class="btn btn-danger" href="member_list.php">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit"
							        data-loading-text="یک لحظه صبر کنید <i class='fa fa-spinner fa-pulse'></i>">
								بساز
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<aside>
			<h2><i class="fa fa-info-circle"></i> دستورالعمل</h2>
			<p>لطفا بخاطر داشته باشید که ستون های اسم کاربری و پسورد ضروری هستند.</p>
			<p>اعضای درست شده توسط مدیران ارشد باید اسم کاربری و پسورد موقتشان به ایشان ابلاغ گردد.</p>
			<p>همینطور آنها احتیاج دارند که عضویت آنها فعال شود قبل از اینکه بخواهند با سیستم کار کنند.</p>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>