<?php require_once('../includes/initialize.php');
if($session->is_logged_in()) redirect_to('member.php');
$filename = basename(__FILE__);
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>

<?php include_layout_template('snippet-carousel.php'); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<?php include_layout_template('article-intro.php'); ?>
	<?php include_layout_template('member_article_info.php'); ?>
</section><!-- main -->
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<?php include_layout_template('google-search.php'); ?>
	<?php include_layout_template('aside-video-promo.php'); ?>
	<?php include_layout_template('aside-register.php'); ?>
	<aside>
		<?php include_layout_template('aside-share.php'); ?>
	</aside>
</section><!-- sidebar -->
<?php include_layout_template('footer.php'); ?>

