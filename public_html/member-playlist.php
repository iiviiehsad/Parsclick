<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - لیست پخش اعضا";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$playlist_set = Playlist::find_playlist_for_member($member->id);
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article class="member_profile">
		<h3><i class="fa fa-floppy-o fa-lg"></i> لیست پخش <?php if( ! $playlist_set) {
				echo "خالی است.";
			} ?></h3>
		<?php if($playlist_set) { ?>
			<span class='badge'>تعداد <?php echo convert(Playlist::count_playlist_for_member($member->id)); ?> درس داخل لیست پخش شماست.</span>
		<?php } else { ?>
			<span class='badge'>هیچ درسی داخل لیست پخش شما نیست.</span>
		<?php } ?>
		<br/><br/>
		<ul class="list-group">
			<?php foreach($playlist_set as $playlist): ?>
				<?php $course = Course::find_by_id($playlist->course_id); ?>
				<li class="list-group-item">
					<a href="member-courses?category=<?php echo urlencode($course->category_id); ?>&course=<?php echo urlencode($course->id); ?>">
						<i class="fa fa-play-circle fa-lg"></i>&nbsp;&nbsp;&nbsp;
						<?php echo htmlentities($course->name); ?>
					</a>
					<a href="remove-from-playlist?playlist=<?php echo urlencode($playlist->id); ?>" class="pull-left">
						<span class="glyphicon glyphicon-remove"></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<h2><i class="fa fa-info-circle"></i> اطلاعات</h2>
		<p>شما قادر به اضافه و پاک کردن هر درسی به لیست پخش هستید. در این قسمت فقط قادر به دیدن و پاک کردن درس از لیست
		   پخش خود هستید. برای اضافه کردن درسی به لیست پخش به بالای صفحه ی هر درس مراجعه کنید و روی دگمه اضافه به لیست
		   پخش کلیک کنید.</p>
		<p>لیست پخش شما به ترتیب تاریخ اضافه شدن مرتب می شوند.</p>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
