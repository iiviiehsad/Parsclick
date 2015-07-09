<?php require_once("../../includes/initialize.php"); ?>
<?php $session->confirm_author_logged_in(); ?>
<?php $author = Author::find_by_id($session->id); ?>
<!DOCTYPE html>
<html>
<head>
	<title>پارس کلیک - Parsclick</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/favicon.png">
	<link rel="stylesheet" href="../_/css/bootstrap.css" media="screen">
	<link rel="stylesheet" href="../_/css/bootstrap-rtl.min.css" media="screen">
	<link rel="stylesheet" href="../_/css/font-awesome.min.css">
	<link rel="stylesheet" href="../_/css/mystyles.css" media="screen">
	<style>
		.jumbotron {
			padding       : 100px;
			margin        : 0.1%;
			font-size     : 24px;
			font-weight   : 200;
			line-height   : 2.14285714;
			color         : inherit;
			border        : 3px solid #415B76;
		}
	</style>
</head>
<body>
<section class="container">
	<div class="content row">
		<header class="clearfix">
			<section id="branding">
				<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Parsclick"></a>
			</section>
		</header>
		<div class="jumbotron">
			<h1><?php echo ucwords(strtolower($author->full_name())); ?></h1>
			<p>متاسفانه به علتی حساب شما از دسترسی به این سایت محدود شده.</p>
			<p>لطفا با مدیر ارشد وب سایت تماس بگیرید.</p>
			<p><a class="btn btn-danger btn-lg" href="logout.php" role="button">خروج</a></p>
		</div>
	</div>
	<section class="container">
		<footer class="row">
			<nav class="col-lg-12">
				<ul class="breadcrumb">
					<li class="arial">
						Copyright &copy; <?php echo strftime("%Y", time()); ?> Parsclick
					</li>
				</ul>
			</nav>
		</footer>
	</section>
</section>
<script src="../_/js/bootstrap.js"></script>
<script src="../_/js/myscript.js"></script>
</body>
</html>
<?php if(isset($database)) {
	$database->close_connection();
} ?>
