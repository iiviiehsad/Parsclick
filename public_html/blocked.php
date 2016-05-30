<?php require_once('../includes/initialize.php');
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
if ($member->status == 0) redirect_to('freezed');
?>
<?php include('../includes/layouts/header.php'); ?>
	<style type="text/css">
		.jumbotron {
			padding     : 50px;
			margin      : 0.1%;
			font-size   : 24px;
			font-weight : 200;
			line-height : 2.14285714;
			color       : inherit;
			border      : 3px solid #475C98;
		}
	</style>
	<header class="clearfix">
		<section id="branding">
			<a href="/"><img src="images/misc/logo.png" alt="Logo for Parsclick"></a>
		</section>
	</header>
	<div class="jumbotron">
		<h1>با عرض پوزش <?php echo $member->full_name(); ?>!</h1>
		<p>حق اشتراک شما به یکی از دلایل زیر بسته شده:</p>
		<ul class="text-warning">
			<li>شما یکی یا چند تا از قوانین سایت را رعایت نکردید.</li>
			<li>عضویت شما موقتا توصط مدیر سایت معلق شده.</li>
			<li>شما مدت طولانی از سیستم استفاده نکردید.</li>
		</ul>
		<p>
			<a class="btn btn-danger btn-large" href="logout.php" role="button">خروج</a>
			<a href="mailto:<?php echo ADMIN_EMAIL; ?>" target="_blank" class="btn btn-success btn-large" role="button">
				تماس با مدیر سایت
			</a>
		</p>
	</div>
<?php include('../includes/layouts/footer.php'); ?>