<?php require_once("../../includes/initialize.php"); ?>
<?php $session->confirm_author_logged_in(); ?>
<?php $author = Author::find_by_id($session->id); ?>
<?php include_layout_template("admin_header.php"); ?>
<header class="clearfix">
	<section id="branding">
		<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Parsclick"></a>
	</section>
</header>
<div class="jumbotron">
	<h1>جناب <?php echo ucwords(strtolower($author->full_name())); ?></h1>
	<p>متاسفانه به علتی حساب شما از دسترسی به این سایت محدود شده.</p>
	<p>لطفا با مدیر ارشد وب سایت تماس بگیرید.</p>
	<p>
		<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
		<a href="mailto:<?php echo EMAILUSER; ?>" class="btn btn-success btn-large" role="button">
			تماس با مدیر سایت
		</a>
	</p>
</div>
<?php include_layout_template("admin_footer.php"); ?>
