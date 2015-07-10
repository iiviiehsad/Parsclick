<?php require_once("../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $title = "پارس کلیک - قسمت اعضا"; ?>
<?php $session->confirm_logged_in(); ?>
<?php $member = Member::find_by_id($session->id); ?>
<?php $member->check_status(); ?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
<div class="jumbotron hidden-sm wow fadeIn play">
	<h1>خوش آمدید <?php echo ucwords(strtolower($member->full_name())); ?></h1>
	<p>
		با تشکر از ملحق شدن به ما اگر اشکالی در این سیستم پیدا کردید، مدیر را در جریان بگذارید.<br/>
		شما دسترسی به یکی از بزرگترین کتابخانه ویدئویی رایگان پارسی زبانان را دارید. لطفا از دوستان خود دعوت کنید که به ما
		بپیوندند.
	</p>
</div>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article class="member_profile">
		<h2>
			<span class="hidden-sm">قابلیت های شما:</span>
			<span class="visible-sm"><?php echo "Welcome " . ucwords(strtolower($member->full_name())); ?></span>
		</h2>

		<h3><a href="member_articles.php"><i class="fa fa-film"></i> دنبال کردن درس ها</a></h3>
		<p>با دنبال کردن درس ها شما به ویدیو ها دسترسی پیدا خواهید کرد. ویدیوهایی که در رابطه با دروس کامپیوتر هستند.
		   این دروس درون دسته ها یا مقوله هایی برای دسترسی آسانتر طبقه بندی شده اند.در اولین صفحه شما قادر به جستجو کردن
		   درس ها هستید اگر دنبال درس خاصی می گردید. هر درسی شامل یک فایل تمرینی می باشد که با دانلود کردن این فایل زیپ
		   تمامی فایل هایی تمرینی بعد از خارج کردن فایل فشرده قابل مشاهده خواهند بود. در ضمن نظر نویسی برای هر درس
		   جداگانه امکان پذیر است.</p>

		<h3><a href="member_courses.php"><i class="fa fa-newspaper-o"></i> دنبال کردن مقالات</a></h3>
		<p>با دنبال کردن مقالات ها شما به مقاله ها دسترسی پیدا خواهید کرد. مقاله هایی که در رابطه با کامپیوتر هستند. این
		   مقاله ها درون موضوعات برای دسترسی آسانتر طبقه بندی شده اند.در اولین صفحه شما قادر به جستجو کردن مقاله ها
		   هستید اگر دنبال مقاله ی خاصی می گردید.</p>

		<h3><a href="member_playlist.php"><i class="fa fa-floppy-o"></i> لیست پخش شما</a></h3>
		<p>وقتی که داخل دسته بندی ها درس های مختلفی دارید، درس ها هر کدام شامل چندین ویدیو هستند و همینطور فایل ها داخل
		   این درس ها گماشته شده اند بنابراین لیست پخش به شما این اجازه را می دهد که این درس ها را که پیدا کردید ذخیره
		   کنید که بعدها برای دسترسی زودرس به آنها مراجعه کنید. اضافه کردن و پاک کردن به لیست پخش شما از داخل هر درس و
		   داخل لیست پخش شما امکان پذیر هستند.</p>

		<h3><a href="member_profile.php"><i class="fa fa-pencil-square-o"></i> ویرایش حساب کاربری</a></h3>
		<p>در این قسمت شما به راحتی می توانید حساب کاربری خود را ویرایش کنید، عکس اضافه کنید، اسم کاربری، پسورد و جزئیات
		   حساب کاربری خود را بروزرسانی کنید. در ضمن تنظیمات و لغو کردن اشتراک در این قسمت امکان پذیر می باشد.</p>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<h2>محتوای کتابخانه</h2>
		<i class="pull-right fa fa-film fa-5x text-danger"></i>
		<p>برای دیدن درس های ویدیویی لطفا روی دگمه ی زیر کلیک کنید
			<br/><a class="btn btn-small btn-danger" href="member_courses.php">دیدن درس ها</a></p>
		<hr class="wow bounceInRight"/>
		<i class="pull-right fa fa-newspaper-o fa-5x text-danger"></i>
		<p>برای دیدن مقالات علمی لطفا روی دگمه ی زیر کلیک کنید
			<br/><a class="btn btn-small btn-danger" href="member_articles.php">دیدن مقالات</a></p>
		<hr class="wow bounceInRight"/>
	</aside>
</section>


<?php include_layout_template("footer.php"); ?>
