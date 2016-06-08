<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
include_layout_template('admin_header.php');
?>
<header class="clearfix">
	<section id="branding">
		<a href="index.php"><img src="<?php echo is_local() ? '../' : '/'; ?>images/misc/admin-area.png" alt="Logo for Parsclick"></a>
	</section>
</header>
<div class="jumbotron">
	<h1>جناب <?php echo $author->full_name(); ?></h1>
	<p>متاسفانه به علتی حساب شما از دسترسی به این سایت محدود شده.</p>
	<p>این علت ممکن هست در رابطه با عدم همکاری با نقض قوانین سایت یا علتی دیگر باشد.</p>
	<p>لطفا با مدیر ارشد وب سایت تماس بگیرید و هنگام تماس شناسه کاربری را ذکر کنید.</p>
	<p>
		<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
		<a class="btn btn-success btn-large" href="mailto:<?php echo ADMIN_EMAIL; ?>" target="_blank" role="button">
			تماس با مدیر سایت
		</a>
	</p>
</div>
<?php include_layout_template('admin_footer.php'); ?>
