<?php require_once('../includes/initialize.php');
$title = 'پارس کلیک - قسمت اعضا';
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
$newest_course  = Course::find_newest_course();
$newest_article = Article::find_newest_article();
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message); ?>

<div class="jumbotron hidden-sm wow fadeIn member-jumbotron">
	<img class="pull-left img-circle" width="150" height="150"
	     src="http://gravatar.com/avatar/<?php echo md5($member->email); ?>?s=150&d=<?php echo '//' . DOMAIN .
			     '/images/misc/default-gravatar-pic.png'; ?>">
	<a class="bright pull-left" href="#" id="notification" title="اعلانات" data-toggle="tooltip">
		<i class="fa fa-bell fa-lg"></i>
	</a>

	<h1><?php echo $member->full_name(); ?></h1>
	<div class="clearfix"></div>
	<?php if ($newest_course): ?>
		<p>جدیدترین درس:&nbsp;
			<a class="bright" title='کلیک کنید' data-toggle="tooltip" data-placement="left"
			   href="member-courses?category=<?php echo $newest_course->category_id; ?>&course=<?php echo $newest_course->id; ?>">
				<?php echo $newest_course->name; ?> </a>&nbsp;</p>
	<?php endif; ?>
	<?php if ($newest_article): ?>
		<p>جدیدترین مقاله:&nbsp;
			<a class="bright" title='کلیک کنید' data-toggle="tooltip" data-placement="left"
			   href="member-articles?subject=<?php echo $newest_article->subject_id; ?>&article=<?php echo $newest_article->id; ?>">
				<?php echo $newest_article->name; ?> </a>&nbsp;</p>
	<?php endif; ?>
	<p>درس های داخل لیست پخش:&nbsp;
		<?php if (Playlist::find_playlist_for_member($member->id)): ?>
			<a class="bright" title='کلیک کنید' data-toggle="tooltip" data-placement="left"
			   href="member-playlist">
				<?php echo convert(Playlist::count_playlist_for_member($member->id)); ?> درس
			</a>
		<?php else: ?>
			هیچی !
		<?php endif; ?>
	</p>
	<p class="edit bright"> GMT امروز <?php echo datetime_to_shamsi(time()); ?></p>
	<div class="clearfix"></div>
	<p class="edit bright">Today is <?php echo datetime_to_text(date('Y-m-d H:i:s')); ?></p>
</div><!--.jumbotron-->
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article>
		<h3>
			<span
					class="label label-info visible-sm"><?php echo 'خوش آمدید  ' .
						ucwords(strtolower($member->full_name())); ?></span>
		</h3>
	</article>
	<?php include_layout_template('recent-article.php'); ?>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside>
		<?php include_layout_template('weblog.php'); ?>
		<p><?php include_layout_template('aside-ad.php'); ?></p>
		<?php $radiofarda_rss = parse_rss_feed('http://www.radiofarda.com/api/z_oqmergq_'); ?>
		<h2 class="bbcnassim">اخبار تکنولوژی - رادیو فردا </h2>
		<?php
		foreach ($radiofarda_rss->channel->item as $item) : ?>
			<a target="_blank" class="bbcnassim" href="<?php echo $item->link; ?>"
			   title="<?php echo $item->description; ?>"
			   data-toggle="tooltip">
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
					<?php echo $item->title; ?>
				</div>
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<img src="<?php echo $item->enclosure['url']; ?>" width="100%" class=" img-rounded screenshot">
				</div>
			</a>
		<?php endforeach; ?>
	</aside>
</section>

<?php include_layout_template('notification.php'); ?>
<?php include_layout_template('footer.php'); ?>
