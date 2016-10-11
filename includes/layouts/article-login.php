<article>
	<h2>فرم ورود</h2>

	<form class="login form-horizontal" action="login" method="post" data-remote>
		<fieldset id="login">
			<legend><i class="fa fa-unlock-alt fa-lg"></i> از فرم زیر استفاده کنید تا وارد سیستم شوید.</legend>
			<section class="row">
				<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="username">اسم کاربری&nbsp;</label>
				<div class="controls">
					<div class="input-group">
						<span class="input-group-addon arial"><span class="glyphicon glyphicon-user"></span></span>
						<input class="col-sm-8 col-md-8 col-lg-8 edit" type="text" name="username" id="username" autofocus
						       placeholder="Username" value="<?php echo isset($username) ? htmlentities($username) : ''; ?>"
						       maxlength="30" required/>
					</div>
				</div><!-- controls -->
			</section><!-- row -->
			<section class="row">
				<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="password">پسورد&nbsp;</label>
				<div class="controls">
					<div class="input-group">
						<span class="input-group-addon arial"><span class="glyphicon glyphicon-lock"></span></span>
						<input class="col-sm-8 col-md-8 col-lg-8 edit" type="password" name="password" id="password"
						       placeholder="Password" maxlength="30" required/>
					</div>
				</div><!-- controls -->
			</section><!-- row -->
			<?php global $session;
			echo $session->csrf_token_tag(); ?>
			<section class="row">
				<label class="col-sm-4 col-md-4 col-lg-4 control-label" for="submit"></label>
				<div class="controls">
					<button class="btn btn-primary" name="submit" id="submit" type="submit"
					        data-loading-text="یک لحظه <i class='fa fa-spinner fa-pulse'></i>">
						ورود
					</button>
				</div><!-- controls -->
			</section><!-- row -->
			<section class="row">
				<a href="forgot" class="col-lg-offset-4 col-md-offset-4 col-sm-offset-4">پسورد یادتون نیست؟</a><br/>
				<a href="register" class="col-lg-offset-4 col-md-offset-4 col-sm-offset-4">ثبت نام</a>
			</section><!--row-->
		</fieldset><!-- personal info -->
	</form>
</article>
<br/>