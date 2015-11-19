<?php
require_once("../includes/initialize.php");
if($session->is_logged_in()) { redirect_to("member.php"); }
$filename = basename(__FILE__);
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/nav.php"); ?>
<?php include("_/components/php/snippet-carousel.php"); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<?php include("_/components/php/article-intro.php"); ?>
</section><!-- main -->
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<?php include("_/components/php/aside-video-promo.php"); ?>
	<?php include("_/components/php/aside-register.php"); ?>
	<?php include("_/components/php/article-facebook-comment.php"); ?>
	<aside>
		<?php include("_/components/php/aside-news.php"); ?>
		<h2>به اشتراک بگذارید</h2>
		<?php include("_/components/php/aside-share.php"); ?>
	</aside>
</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>
