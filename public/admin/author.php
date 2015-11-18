<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message);
?>
<div class="jumbotron hidden-sm wow fadeIn author-jumbotron">
	<h1>خوش آمدید نویسنده: <?php echo $author->full_name(); ?></h1>
	<p>به عنوان نویسنده شما قادر به درست کردن مقاله و درس هستید. شما همینطور قادر به تغییر مقالات و دروس خود هستید.</p>
</div>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<h2>کارهایی که میتوانید انجام دهید:</h2>
		<br/><br/>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow bounceInUp">
			<a href="author_articles.php" title="Create Articles">
				<i style="height:130px; font-size: 700%;" class="center fa fa-newspaper-o fa-fw"></i>
				<h4 class="center">مقاله درست کنید</h4>
			</a>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow bounceInUp" data-wow-delay=".3s">
			<a href="author_courses.php" title="Create Course">
				<i style="height:130px; font-size: 700%;" class="center fa fa-film fa-fw"></i>
				<h4 class="center">درس درست کنید</h4>
			</a>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow bounceInUp" data-wow-delay=".6s">
			<a href="author_profile.php" title="Edit Your Profile">
				<i style="height:130px; font-size: 700%;" class="center fa fa-pencil-square-o fa-fw"></i>
				<h4 class="center">حساب کاربری ویرایش کنید</h4>
			</a>
		</div>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<h2><i class="fa fa-newspaper-o"></i> مقالات</h2>
		<p>برای ساختن مقاله روی دگمه ی زیر کلیک کنید:</p>
		<a class="btn btn-danger" href="author_articles.php">مقالات</a>

		<h2><i class="fa fa-film"></i> دروس</h2>
		<p>برای ساختن درس روی دگمه ی زیر کلیک کنید:</p>
		<a class="btn btn-danger" href="author_courses.php">دروس</a>
	</aside>
</section>
<?php include_layout_template("admin_footer.php"); ?>
