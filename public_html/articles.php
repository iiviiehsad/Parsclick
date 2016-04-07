<?php require_once('../includes/initialize.php');
if($session->is_logged_in()) redirect_to('member-articles.php');
$filename = basename(__FILE__);
find_selected_article(TRUE);
$newest_article = Article::find_newest_article();
$errors         = '';
$body           = '';
if(isset($current_article->author_id)) {
	$author = Author::find_by_id($current_article->author_id);
}
$title = isset($current_article) ? 'پارس کلیک - ' . $current_article->name : 'پارس کلیک - ' . $newest_article->name;
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article id="member_article">
		<?php if($current_subject && $current_article): ?>
			<h2><?php echo htmlentities($current_article->name); ?></h2>
			<h5>
				<?php if(isset($author)): ?>
					<i class="fa fa-user fa-lg"></i>&nbsp;
					<?php echo "توسط: " . $author->full_name();
					if( ! empty($author->photo)): ?>
						<img class="author-photo img-circle pull-left" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
					<?php endif;
				endif; ?>
			</h5>
			<h5>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?>
			</h5>
			<h5>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_shamsi($current_article->created_at)); ?>
			</h5>
			<hr/>
			<?php echo nl2br(strip_tags($current_article->content, ARTICLE_ALLOWABLE_TAGS)); ?>
			<hr/>
			<article id="comments">
				<div class="badge">برای اظهار نظر لطفا عضو شوید.</div>
				<?php include_layout_template('article-comments.php'); ?>
			</article>
		<?php else: ?>
			<?php $current_article = $current_subject = $newest_article; ?>
			<h2>
				<a href="articles?subject=<?php echo urlencode($newest_article->subject_id); ?>&article=<?php echo urlencode($newest_article->id); ?>" title="کلیک کنید" data-toggle="tooltip">
					<?php echo htmlentities($newest_article->name); ?>
				</a><span class="badge">جدیدترین مقاله</span>
			</h2>
			<h5>
				<?php if(isset($newest_article->author_id)):
					$author = Author::find_by_id($newest_article->author_id);
					?><i class="fa fa-user fa-lg"></i>&nbsp;
					<?php echo "توسط: " . $author->full_name();
					if( ! empty($author->photo)): ?>
						<img class="author-photo img-circle pull-left" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
					<?php endif; ?>
				<?php endif; ?>
			</h5>
			<h5>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($newest_article->created_at)); ?>
			</h5>
			<h5>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_shamsi($newest_article->created_at)); ?>
			</h5>
			<hr>
			<?php echo nl2br(strip_tags($newest_article->content, ARTICLE_ALLOWABLE_TAGS)); ?>
			<hr/>
			<article id="comments">
				<div class="badge">برای اظهار نظر لطفا عضو شوید.</div>
				<?php include_layout_template('article-comments.php'); ?>
			</article>
		<?php endif; ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="members_menu">
		<?php include_layout_template('aside-share.php'); ?>
		<h2>موضوعات و مقالات</h2>
		<?php echo public_articles($current_subject, $current_article); ?>
		<h2>وبلاگ پارس کلیک</h2>
		<p>برای اخبار، کوپن های یودمی، خبر از درس های آینده، پادکست، صحبت از نویسندگان، صحبت از مقالات خوب، لینک دانلود
		   ویدیو و خیلی چیزهای دیگه به وبلاگ پارس کلیک سر بزنید.</p>
		<ul class="list-group">
			<li class="list-group-item">
				<a href="http://blog.parsclick.net/" target="_blank">وبلاگ پارس کلیک</a>
				<span class="label label-as-badge label-danger pull-left">اخبار</span>
			</li>
		</ul>
		<?php include_layout_template('aside-read.php'); ?>
		<?php include_layout_template('aside-ad.php'); ?>
	</aside>
</section>
<?php include_layout_template('footer.php'); ?>
