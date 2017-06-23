</div>
<section class="container container-footer">
	<footer class="row">
		<nav class="col-lg-12">
			<ul class="breadcrumb col-lg-6">
				<li><a href="<?php echo is_local() ? '' : 'index'; ?>">خانه</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>about">درباره ما</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>privacypolicy">شرایط و ضوابط</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>courses">درس ها</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>articles">مقاله ها</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>faq">سوالات شما</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>help">کمک به سایت</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>login">ورود</a></li>
				<li><a href="<?php echo is_local() ? '' : '/'; ?>register">ثبت نام</a></li>
			</ul>
			<div class="text-left text-white col-lg-6">Copyright &copy; <?php echo date('Y'); ?> Parsclick</div>
			<div class="col-lg-4 pull-left">
				<?php include_layout_template('aside-news.php'); ?>
			</div>
			<ul class="breadcrumb col-lg-6 pull-right">
				<li>
					<a target="_blank" href="https://www.facebook.com/persiantc">
						<i title="Facebook" class="fa fa-facebook-square fa-3x"></i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://twitter.com/AmirHassanAzimi">
						<i title="Twitter" class="fa fa-twitter-square fa-3x"></i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://www.youtube.com/user/PersianComputers">
						<i title="YouTube" class="fa fa-youtube-square fa-3x"></i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://plus.google.com/+PersianComputers/posts">
						<i title="Google+" class="fa fa-google-plus-square fa-3x"></i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://www.linkedin.com/in/hass0azimi">
						<i title="LinkedIn" class="fa fa-linkedin-square fa-3x"></i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://github.com/hassanazimi">
						<i title="GitHub" class="fa fa-github-square fa-3x"></i>
					</a>
				</li>
			</ul>
		</nav>
	</footer>
</section>
</section>
<script src="<?php echo is_local() ? '' : '/'; ?>_/js/all.js"></script>
<?php include_layout_template('google_analytic.php'); ?>
</body>
</html>
<?php if (isset($database)) $database->close_connection(); ?>