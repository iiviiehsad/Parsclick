<?php require_once("../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $title = "پارس کلیک - سوالات و جواب ها"; ?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/nav.php"); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<?php include("_/components/php/article-faq.php"); ?>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include("_/components/php/aside-register.php"); ?>
		<?php include("_/components/php/aside-twitter.php"); ?>
	</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>