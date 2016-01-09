<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - درباره ما";
?>
<?php include_layout_template("header.php"); ?>
<?php include "_/components/php/nav.php"; ?>
	<section class="main col-sm-12 col-md-9 col-lg-9">
		<?php include "_/components/php/aboutus.php" ?>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-3 col-lg-3">
		<aside class="socialmedia">
			<?php include "_/components/php/aside-twitter.php" ?>
			<div class="center">
				<hr/>
				<img src="images/others/All.png" alt="Programming Languages"/>
				<hr/>
			</div><!-- center -->
		</aside><!-- socialmedia -->
		<?php include('_/components/php/aside-ad.php'); ?>
	</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>