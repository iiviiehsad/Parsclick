<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
include_layout_template('admin_header.php');
?>
<header class="clearfix">
	<section id="branding">
		<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Parsclick"></a>
	</section>
</header>
<div class="jumbotron">
	<h1>جناب <?php echo ucwords(strtolower($author->full_name())); ?></h1>
	<p>متاسفانه به علتی حساب شما از دسترسی به این سایت محدود شده.</p>
	<p>این علت ممکن هست در رابطه با عدم همکاری با نقض قوانین سایت یا علتی دیگر باشد.</p>
	<p>لطفا با مدیر ارشد وب سایت تماس بگیرید و هنگام تماس شناسه کاربری را ذکر کنید.</p>
	<p>
		<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
		<a href="../contact" class="btn btn-success btn-large" role="button">
			تماس با مدیر سایت
		</a>
	</p>
</div>
<?php include_layout_template('admin_footer.php'); ?>
