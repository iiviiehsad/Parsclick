<article>
	<h2>فرم ثبت نام</h2>
	<p>لطفا از فرم ثبت نام زیر استفاده کنید و اطلاعات خود را وارد نمایید <br/>
		<span class="text-success"><i class="fa fa-info-circle fa-lg"></i> تمام فیلدهایی که با (*) علامت گذاری شده اند اجباری هستند.</span><br/>
		<span class="text-success"><i class="fa fa-info-circle fa-lg"></i> پسورد باید ۶ حرف یا بیشتر باشد.</span><br/>
		<span class="text-success"><i class="fa fa-info-circle fa-lg"></i> پسورد باید حداقل شامل یک حروف مخصوص باشد مثل <span style="font-family:Arial;">(!@£$%^&*[]{}<>-+=~±§#¢∞\|/)</span></span><br/>
		<span class="text-success"><i class="fa fa-info-circle fa-lg"></i> قبل از ثبت نام شرایط و ضوابط را مطالعه کنید.
	</p>

	<form class="registration form-horizontal" action="register.php" method="POST" id="payment-form">
		<fieldset>
			<legend><i class="fa fa-credit-card fa-lg"></i> ثبت نام
				<small><span class="pull-left wow flash infinite" data-wow-duration="2s" id="confirmMessage"></span>
				</small></legend>

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری * &nbsp;</label>
				<div class="controls">
					<input onblur="checkUser();" onkeyup="checkUser();" class="col-xs-8 col-sm-8 col-md-8 col-lg-8 edit" type="text" maxlength="50" name="username" id="username" placeholder="Username (حروف انگلیسی)" required pattern="[a-zA-Z0-9_.]+" value="<?php echo $_POST["username"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد * &nbsp;</label>
				<div class="controls">
					<input onblur="checkPass();" onkeyup="checkPass();" class="col-xs-8 col-sm-8 col-md-8 col-lg-8 edit" type="password" maxlength="50" name="password" id="password" placeholder="Password" required pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="confirm_pass">تایید پسورد  * &nbsp;</label>
				<div class="controls">
					<input onblur="checkConfirmPass();" onkeyup="checkConfirmPass();" class="col-xs-8 col-sm-8 col-md-8 col-lg-8 edit" type="password" maxlength="50" name="confirm_pass" id="confirm_pass" placeholder="Confirm Password" required pattern="(?=^.{6,}$)((?=.*\W+))(?![.\n]).*$"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="email">ایمیل * &nbsp;</label>
				<div class="controls">
					<input onblur="checkEmail();" onkeyup="checkEmail();" class="col-xs-8 col-sm-8 col-md-8 col-lg-8 edit" type="email" maxlength="50" name="email" id="email"  placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$" value="<?php echo $_POST["email"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="first_name">نام * &nbsp;</label>
				<div class="controls">
					<input onblur="checkfirstname();" class="col-xs-8 col-sm-8 col-md-8 col-lg-8" type="text" maxlength="50" name="first_name" id="first_name"  placeholder="نام" required value="<?php echo $_POST["first_name"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="last_name">نام خانوادگی * &nbsp;</label>
				<div class="controls">
					<input onblur="checklastname();" class="col-xs-8 col-sm-8 col-md-8 col-lg-8" type="text" maxlength="50" name="last_name" id="last_name"  placeholder="نام خانوادگی" required value="<?php echo $_POST["last_name"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

		</fieldset>
		<br/>
		<fieldset>
			<legend><i class="fa fa-archive fa-lg"></i> جزئیات اختیاری </legend>
			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="gender">جنس &nbsp;</label>
				<div class="controls">
					<select class="form-control col-xs-8 col-sm-4 col-md-4 col-lg-4" name="gender" id="gender">
						<optgroup label="لطفا انتخاب کنید">
							<option value="مرد">مرد</option>
							<option value="زن">زن</option>
						</optgroup>
					</select>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="address">آدرس &nbsp;</label>
				<div class="controls">
					<input class="col-xs-8 col-sm-8 col-md-8 col-lg-8" type="text" name="address" id="address"  placeholder="آدرس" value="<?php echo $_POST["address"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="city">شهر &nbsp;</label>
				<div class="controls">
					<input class="col-xs-8 col-sm-8 col-md-8 col-lg-8" type="text" name="city" id="city"  placeholder="شهر" value="<?php echo $_POST["city"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="post_code">کد پستی &nbsp;</label>
				<div class="controls">
					<input class="col-xs-8 col-sm-8 col-md-8 col-lg-8" type="text" name="post_code" id="post_code"  placeholder="کد پستی" value="<?php echo $_POST["post_code"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->

			<section class="row">
				<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="phone">تلفن &nbsp;</label>
				<div class="controls">
					<input class="col-xs-8 col-sm-8 col-md-8 col-lg-8" type="tel" name="phone" id="phone" placeholder="تلفن" value="<?php echo $_POST["phone"]; ?>"/>
				</div><!-- controls -->
			</section><!-- row -->
		</fieldset>

		<section class="row">
			<div class="pull-left text-danger checkbox col-xs-8 col-sm-8 col-md-8 col-lg-8">
				<label class="col-xs-8 col-sm-7 col-md-6 col-lg-5">
					<input type="checkbox" required><a href="privacypolicy.php" target="_blank" title="شرایط و ضوابط">شرایط را قبول دارم *</a>
				</label>
			</div><!-- checkbox -->
		</section><!-- row -->

		<section class="row">
			<label class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
			<div class="controls">
				<a href="login.php" class="btn btn-danger">لغو کردن</a>
				<button class="btn btn-primary" name="submit" type="submit">ثبت نام</button>
			</div><!-- controls -->
		</section><!-- row -->
	</form>
</article>
<br/>