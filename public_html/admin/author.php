<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
$articles_under_edit = Article::find_articles_for_author($author->id, FALSE);
$articles_for_author = Article::find_articles_for_author($author->id, TRUE);
$courses_under_edit  = Course::find_courses_for_author($author->id, FALSE);
$courses_for_author  = Course::find_courses_for_author($author->id, TRUE);
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message);
?>
<div class="jumbotron hidden-sm wow fadeIn author-jumbotron">
	<?php if( ! empty($author->photo)) { ?>
		<img class="img-circle pull-left" height="200" width="200" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>">
	<?php } else { ?>
		<img class="img-circle pull-left" height="200" width="200" src="../images/misc/default-gravatar-pic.png" alt="No Profile Picture">
	<?php } ?>
	<h1>خوش آمدید نویسنده: <?php echo $author->full_name(); ?></h1>
	<p>
		به عنوان نویسنده شما قادر به درست کردن مقاله و درس هستید. شما همینطور قادر به تغییر مقالات و دروس خود هستید.<br/>
		برای تماس مستقیم با مدیر از این آدرس بجای تماس با ما استفاده کنید:
		<code>info@parsclick.net</code><br/>
		تماس با ما برای اعضاست و هر دو هفته یکبار جواب داده می شود. آدرس بالا دسترسی مستقیم با مدیران است.
	</p>
</div>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<?php if($articles_under_edit): ?>
			<h3>
				<span class="label label-danger label-as-badge"><?php echo convert(count($articles_under_edit)); ?></span>
				مقالات زیر بررسی:
			</h3>
			<ul class="fa-ul">
				<?php foreach($articles_under_edit as $aue): ?>
					<li>
						<i class='fa fa-refresh fa-spin text-danger'></i>&nbsp;
						<a href="author_articles.php?subject=<?php echo urlencode($aue->subject_id); ?>&article=<?php echo urlencode($aue->id); ?>">
							<?php echo $aue->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if($courses_under_edit): ?>
			<h3>
				<span class="label label-danger label-as-badge"><?php echo convert(count($courses_under_edit)); ?></span>
				درس های زیر بررسی:
			</h3>
			<ul class="fa-ul">
				<?php foreach($courses_under_edit as $cue): ?>
					<li>
						<i class='fa fa-refresh fa-spin text-danger'></i>&nbsp;
						<a href="author_courses.php?category=<?php echo urlencode($cue->subject_id); ?>&course=<?php echo urlencode($cue->id); ?>">
							<?php echo $cue->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if($articles_for_author): ?>
			<h3>
				<span class="label label-success label-as-badge"><?php echo convert(count($articles_for_author)); ?></span>
				مقاله منتشر شده توسط شما:
			</h3>
			<ul class="fa-ul">
				<?php foreach($articles_for_author as $afa): ?>
					<li>
						<i class="fa fa-check-square text-success"></i>
						<a href="author_articles.php?subject=<?php echo urlencode($afa->subject_id); ?>&article=<?php echo urlencode($afa->id); ?>">
							<?php echo $afa->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if($courses_for_author): ?>
			<h3>
				<span class="label label-success label-as-badge"><?php echo convert(count($courses_for_author)); ?></span>
				درس منتشر شده توسط شما:
			</h3>
			<ul class="fa-ul">
				<?php foreach($courses_for_author as $cfa): ?>
					<li>
						<i class="fa fa-check-square text-success"></i>
						<a href="author_courses.php?category=<?php echo urlencode($cfa->category_id); ?>&course=<?php echo urlencode($cfa->id); ?>">
							<?php echo $cfa->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if( ! $articles_under_edit && ! $articles_for_author): ?>
			<p>تشکر ویژه ما رو پذیرا باشید چون ما رو انتخاب کردید برای منتشر کردن مقالات خودتون. امیدوارم که بتونید به راحتی
			   با کاربرای خودتوی در ارتباط باشید، و اگر قابلیتی از این سیستم می خواهید با ما در میون بگذارید. اما ...</p>
			<p class="lead text-danger">توجه!</p>
			<p>شما در حال حاضر مقاله ای ندارید. بهتر هست تا چند وقت دیگه یک فکری به حال مقاله سازی کنید قبل از اینکه مدیر
			   سایت به خاطر غیر فعال بودن به مدت طولانی حساب شما رو مسدود کنه.</p>
			<p>مدیر عضویت نویسندگی نویسنده ای رو بی دلیل مسدود نمی کند و به نویسندگان خیلی احترام قایل هست. اما به عنوان
			   نویسنده ای
			   که تازه شروع به کار کردید بهتر هست که مقاله ای آماده کنید و اون رو بفرستید چون دلیلی برای نگه داشتن نویسنده ی
			   غیر فعال اینجا پیدا نمی کنیم.</p>
			<p class="lead pull-left">با تشکر از شمـا</p>
		<?php endif; ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<h2><i class="fa fa-newspaper-o"></i> مقالات</h2>
		<p>برای ساختن مقاله روی دگمه ی زیر کلیک کنید:</p>
		<a class="btn btn-primary" href="author_articles.php">مقالات</a>
		<h2><i class="fa fa-film"></i> دروس</h2>
		<p>برای ساختن درس روی دگمه ی زیر کلیک کنید:</p>
		<a class="btn btn-primary" href="author_courses.php">دروس</a>
	</aside>
</section>
<?php include_layout_template("admin_footer.php"); ?>
