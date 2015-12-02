<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - قسمت اعضا";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$newest_course  = Course::find_newest_course();
$newest_article = Article::find_newest_article();
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
<div class="jumbotron hidden-sm wow fadeIn member-jumbotron">
	<h1>خوش آمدید <?php echo ucwords(strtolower($member->full_name())); ?></h1>
	<p>شما دسترسی به یکی از بزرگترین کتابخانه ویدئویی رایگان پارسی زبانان را دارید. لطفا از دوستان خود دعوت کنید که به ما
		بپیوندند.</p>
	<p>
		<?php if($newest_course) { ?>
			<span>جدیدترین درس:</span>&nbsp;
			<a style="color: #FF8000;" title='کلیک کنید' data-toggle="tooltip" data-placement="left"
			   href="member-courses?category=<?php echo $newest_course->category_id; ?>&course=<?php echo $newest_course->id; ?>">
				<?php echo $newest_course->name; ?> </a>&nbsp;
		<?php } ?>
		<br/>
		<?php if($newest_article) { ?>
			<span>جدیدترین مقاله:</span>&nbsp;
			<a style="color: #FF8000;" title='کلیک کنید' data-toggle="tooltip" data-placement="left"
			   href="member-articles?subject=<?php echo $newest_article->subject_id; ?>&article=<?php echo $newest_article->id; ?>">
				<?php echo $newest_article->name; ?> </a>&nbsp;
		<?php } ?>
	</p>
</div>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<h2><span class="visible-sm"><?php echo "خوش آمدید  " . ucwords(strtolower($member->full_name())); ?></span></h2>

		<h2>جدیدترین مقاله</h2>
		<h5 class="text-success">
			<?php
			if(isset($newest_article->author_id)) {
				$author = Author::find_by_id($newest_article->author_id);
				?><i class="fa fa-user fa-lg"></i>&nbsp;
				<?php echo "توسط: " . $author->full_name();
			} ?>
		</h5>
		<h5 class="text-success">
			<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($newest_article->created_at)); ?>
		</h5>

		<h3><?php echo htmlentities($newest_article->name); ?></h3>
		<?php echo truncate(nl2br(strip_tags($newest_article->content,
		                                     '<h3><h4><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd>')), 1000,
		                    "...<a class='text-danger' href='member-articles?subject={$newest_article->subject_id}&article={$newest_article->id}'>&nbsp;<strong><i>برای ادامه کلیک کنید</i></strong></a>"); ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<h2><i class="fa fa-book"></i> محتوای کتابخانه</h2>
		<i class="pull-right fa fa-film fa-5x text-danger"></i>
		<p>با دنبال کردن درس ها شما به ویدیو ها دسترسی پیدا خواهید کرد. ویدیوهایی که در رابطه با دروس کامپیوتر هستند.
		   این دروس درون دسته ها یا مقوله هایی برای دسترسی آسانتر طبقه بندی شده اند.در اولین صفحه شما قادر به جستجو کردن
		   درس ها هستید اگر دنبال درس خاصی می گردید. هر درسی شامل یک فایل تمرینی می باشد که با دانلود کردن این فایل زیپ
		   تمامی فایل هایی تمرینی بعد از خارج کردن فایل فشرده قابل مشاهده خواهند بود. در ضمن نظر نویسی برای هر درس
		   جداگانه امکان پذیر است.
			<br/><a class="btn btn-small btn-danger" href="member-courses">دیدن درس ها</a></p>
		<hr/>
		<i class="pull-right fa fa-newspaper-o fa-5x text-danger"></i>
		<p>با دنبال کردن مقالات ها شما به مقاله ها دسترسی پیدا خواهید کرد. مقاله هایی که در رابطه با کامپیوتر هستند. این
		   مقاله ها درون موضوعات برای دسترسی آسانتر طبقه بندی شده اند.در اولین صفحه شما قادر به جستجو کردن مقاله ها
		   هستید اگر دنبال مقاله ی خاصی می گردید.
			<br/><a class="btn btn-small btn-danger" href="member-articles">دیدن مقالات</a></p>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
