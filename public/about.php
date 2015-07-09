<?php require_once("../includes/initialize.php");?>
<?php $filename = basename(__FILE__); ?>
<?php $title = "پارس کلیک - درباره ما"; ?>
<?php include_layout_template("header.php"); ?>
<?php include "_/components/php/nav.php"; ?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<?php include "_/components/php/aboutus.php" ?>
</section><!-- main -->
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<?php include "_/components/php/socialmedia.php" ?>
</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>