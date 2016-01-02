<?php
require_once("../includes/initialize.php");
if($session->is_logged_in()) {
	redirect_to("member.php");
}
$filename = basename(__FILE__);
find_selected_article(TRUE);
$newest_article = Article::find_newest_article();
$errors         = "";
$body           = "";
if(isset($current_article->author_id)) {
	$author = Author::find_by_id($current_article->author_id);
}
if(isset($current_article)) {
	$title = "پارس کلیک - " . $current_article->name;
} else {
	$title = "پارس کلیک - " . $newest_article->name;
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/nav.php"); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article id="member_article">
		<?php if($current_subject && $current_article) { ?>
			<?php
			// Pagination
			$page        = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
			$per_page    = 10;
			$total_count = ArticleComment::count_comments_for_article($current_article->id);
			$pagination  = new pagination($page, $per_page, $total_count);
			$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
			?>
			<h2><?php echo htmlentities($current_article->name); ?></h2>
			<h5>
				<?php if(isset($author)) { ?>
					<i class="fa fa-user fa-lg"></i>&nbsp;
					<?php echo "توسط: " . $author->full_name();
					if( ! empty($author->photo)) { ?>
						<img class="author-photo img-circle pull-left" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
					<?php }
				} ?>
			</h5>
			<h5>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?>
			</h5>
			<hr/>
			<?php echo nl2br(strip_tags($current_article->content, '<h2><h3><h4><h5><h6><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd><img><a>')); ?>
			<hr/>
			<article id="comments">
				<h2>نظرات</h2>
				<div class="badge">برای اظهار نظر لطفا عضو شوید.</div>
				<?php foreach($comments as $comment) { ?>
					<section class="media">
						<?php $_member = Member::find_by_id($comment->member_id); ?>
						<img class="img-circle pull-right" width="50" style="padding-right:0;" src="//www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->username; ?>">
						<div class="media-body">
							<span class="label label-as-badge label-success"><?php echo htmlentities($_member->first_name); ?></span>
							<span class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
							<br/>
							<?php echo nl2br(strip_tags($comment->body, '<strong><em><p><pre>')); ?>
						</div>
					</section>
				<?php } // end foreach comments ?>
				<?php if($pagination->total_page() > 1) { ?>
					<nav class="clearfix center">
						<ul class="pagination">
							<?php if($pagination->has_previous_page()) { ?>
								<li>
									<a href="articles?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id); ?>&page=<?php echo urlencode($pagination->previous_page()); ?>#comments" aria-label="Previous">
										<span aria-hidden="true"> &lt;&lt; </span>
									</a>
								</li>
							<?php } // end: if($pagination->has_previous_page()) ?>
							<?php for($i = 1; $i < $pagination->total_page() + 1; $i++) { ?>
								<?php if($i == $page) { ?>
									<li class="active">
										<span><?php echo $i; ?></span>
									</li>
								<?php } else { ?>
									<li>
										<a href="articles?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id); ?>&page=<?php echo urlencode($i); ?>#comments"><?php echo $i; ?></a>
									</li>
								<?php } ?>
							<?php } ?>
							<?php if($pagination->has_next_page()) { ?>
								<li>
									<a href="articles?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id) ?>&page=<?php echo urlencode($pagination->next_page()); ?>#comments" aria-label="Next">
										<span aria-hidden="true">&gt;&gt;</span>
									</a>
								</li>
							<?php } // end: if($pagination->has_next_page()) ?>
						</ul>
					</nav>
				<?php } // end pagination ?>
				<?php if(empty($comments)) { ?>
					<div class="badge">نظری وجود ندارد.</div>
				<?php } ?>
			</article>
		<?php } else { ?>
			<h2 class="text-danger">آخرین مقاله:</h2>
			<h3>
				<a href="articles?subject=<?php echo urlencode($newest_article->subject_id); ?>&article=<?php echo urlencode($newest_article->id); ?>" title="کلیک کنید">
					<?php echo htmlentities($newest_article->name); ?>
				</a>
			</h3>
			<h5>
				<?php
				if(isset($newest_article->author_id)) {
					$author = Author::find_by_id($newest_article->author_id);
					?><i class="fa fa-user fa-lg"></i>&nbsp;
					<?php echo "توسط: " . $author->full_name();
					if( ! empty($author->photo)) { ?>
						<img class="author-photo img-circle pull-left" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
					<?php }
				} ?>
			</h5>
			<h5>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($newest_article->created_at)); ?>
			</h5>
			<hr>
			<?php echo nl2br(strip_tags($newest_article->content, '<h2><h3><h4><h5><h6><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd><img><a>')); ?>
		<?php } ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="members_menu">
		<?php include("_/components/php/aside-share.php"); ?>
		<h2>موضوعات و مقالات</h2>
		<?php echo public_articles($current_subject, $current_article); ?>
		<h2>وبلاگ پارس کلیک</h2>
		<ul class="list-group">
			<li class="list-group-item">
				<a href="http://parsclick.blogspot.co.uk/" target="_blank">وبلاگ پارس کلیک</a>
				<span class="label label-as-badge label-danger pull-left">اخبار</span>
			</li>
		</ul>
		<?php include("_/components/php/aside-read.php"); ?>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
