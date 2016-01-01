<article class="loginform">
	<h2>ورود به عنوان مدیر</h2>
	<p>لطفا به عنوان مدیر ارشد و یا نویسنده وارد شوید. <br/>
	   برای ثبت نام به عنوان نویسنده با مدیر سایت تماس بگیرید. <br>
	   اگر به عنوان کاربر وارد سیستم شدید به محض ورود به عنوان مدیر از قسمت اعضا خارج خواهید شد،</p>
	<form class="login form-horizontal" action="index.php" method="post">
		<fieldset id="login">
			<legend><i class="fa fa-unlock-alt"></i> فرم ورود</legend>
			<section class="row">
				<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری</label>
				<div class="controls">
					<div class="input-group">
						<span class="input-group-addon arial"><span class="glyphicon glyphicon-user"></span></span>
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" autofocus placeholder="Username" value="<?php echo htmlentities($username); ?>" maxlength="30" required/>
					</div>
				</div><!-- controls -->
			</section><!-- row -->
			<section class="row">
				<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد</label>
				<div class="controls">
					<div class="input-group">
						<span class="input-group-addon arial"><span class="glyphicon glyphicon-lock"></span></span>
						<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password" placeholder="Password" value="<?php echo htmlentities($password); ?>" maxlength="30" required/>
					</div>
				</div><!-- controls -->
			</section><!-- row -->
			<section class="row">
				<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="type">نوع</label>
				<div class="controls">
					<label class="radio-inline" for="admin">
						<input type="radio" name="type" id="admin" value="admin" required> مدیر
					</label>
					<label class="radio-inline" for="author">
						<input type="radio" name="type" id="author" value="author" required> نویسنده
					</label>
				</div><!-- controls -->
			</section><!-- row -->
			<?php echo $session->csrf_token_tag(); ?>
			<section class="row">
				<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
				<div class="controls">
					<button class="btn btn-primary" name="submit" id="submit" type="submit">ورود</button>
				</div><!-- controls -->
			</section><!-- row -->
			<section class="row">
				<a href="forgot_password.php" class="col-sm-offset-4 col-md-offset-4 col-lg-offset-4">اسم کاربری یا پسورد یادتان نیست؟</a><br/>
			</section>
		</fieldset><!-- personal info -->
	</form>
</article>
<br/>