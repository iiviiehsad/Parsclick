<?php require_once("../../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $session->confirm_admin_logged_in(); ?>
<?php include_layout_template("admin_header.php"); ?>
<?php include("../_/components/php/admin_nav.php"); ?>
<?php $admin = Admin::find_by_id($session->id); ?>

<div class="jumbotron">
	<h1>خوش آمدید <?php echo $admin->full_name(); ?></h1>
	<p>به عنوان مدیر ارشد سایت شما قادر به هر کاری در این سیستم هستید.</p>
</div>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article class="admin_profile">
		<h2>کارهایی که می توانید انجام دهید:</h2>
		<br/><br/>
		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 wow bounceInUp">
			<a href="member_list.php" title="Member List">
				<i style="height:100px; font-size: 500%;" class="center fa fa-users fa-fw"></i>
				<h4 class="center">اداره اعضا</h4>
			</a>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 wow bounceInUp" data-wow-delay=".3s">
			<a href="admin_articles.php" title="Articles">
				<i style="height:100px; font-size: 500%;" class="center fa fa-newspaper-o fa-fw"></i>
				<h4 class="center">اداره مقالات</h4>
			</a>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 wow bounceInUp" data-wow-delay=".6s">
			<a href="admin_courses.php" title="Courses">
				<i style="height:100px; font-size: 500%;" class="center fa fa-film fa-fw"></i>
				<h4 class="center">اداره دروس</h4>
			</a>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 wow bounceInUp" data-wow-delay=".9s">
			<a href="admin_list.php" title="Admin List">
				<i style="height:100px; font-size: 500%;" class="center fa fa-users fa-fw"></i>
				<h4 class="center">اداره مدیران</h4>
			</a>
		</div>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="admin_menu">
		<h2><i class="fa fa-bullseye"></i> مشاهده فایل ثبت</h2>
		<div class="alert alert-info">
			<i class="fa fa-info-circle fa-lg"></i> <strong>فایل ثبت چیست؟</strong>
			<br/>
			فایل ثبت به شما اجازه مشاهده <b>تمام کارهای مدیران</b> اعم از <b>زمان و تاریخ</b> آخرین باری که مدیر مورد
			نظر وارد سیستم شد را می دهد. به عنوان مدیر <b>شما قادر به پاک کردن اطلاعات فایل ثبت هستید،</b> اما مدیران
			دیگر سایت قادر به مشاهده ی اینکه چه مدیری آخرین بار فایل ثبت را پاک کرده هستند. بنابراین، مطمئن شوید که می
			خواهید اینکار را انجام دهید قبل از اینکه انجام دهید. <br/>
			<a class="btn btn-primary" href="logfile.php">دیدن فایل ثبت</a>
		</div>
	</aside>
</section>
<?php include_layout_template("admin_footer.php"); ?>
