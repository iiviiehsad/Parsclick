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
			<?php
			// Pagination
			$page        = ! empty($_GET['page']) ? (int) $_GET['page'] : 1;
			$per_page    = 10;
			$total_count = ArticleComment::count_comments_for_article($current_article->id);
			$pagination  = new pagination($page, $per_page, $total_count);
			$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
			?>
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
				<h3>
					<i class="fa fa-comments-o fa-2x"></i>
					<?php if( ! empty($comments)): ?>
						<span class="label label-as-badge label-info"><?php echo convert(count($current_article->comments())); ?>
							نظر</span>
					<?php else: ?>
						<span class="label label-as-badge label-danger">نظری وجود ندارد</span>
					<?php endif; ?>
				</h3>
				<div class="badge">برای اظهار نظر لطفا عضو شوید.</div>
				<?php foreach($comments as $comment): ?>
					<section class="media">
						<?php $_member = Member::find_by_id($comment->member_id); ?>
						<img class="img-circle pull-right" width="50" style="padding-right:0;" src="//www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->username; ?>">
						<div class="media-body">
							<span class="label label-as-badge label-success"><?php echo htmlentities($_member->first_name); ?></span>
							<span class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_shamsi($comment->created)); ?></span>
							<br/>
							<?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?>
						</div>
					</section>
				<?php endforeach; ?>
				<?php echo paginate($pagination, $page, 'articles', "subject={$current_article->subject_id}", "article={$current_article->id}#comments"); ?>
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
