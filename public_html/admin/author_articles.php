<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
$filename = basename(__FILE__);
find_selected_article();
include_layout_template('admin_header.php');
include_layout_template('author_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_subject && $current_article): ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo $current_article->visible == 1 ? '<i class="fa fa-eye"></i>' : '<i class="text-danger fa fa-eye-slash"></i>'; ?>
							<?php echo htmlentities(ucwords($current_article->name)); ?>
						</h3>
						<h5>
							<?php if(isset($current_article->author_id)): ?>
								<?php $_author = Author::find_by_id($current_article->author_id); ?>
								<i class="fa fa-user fa-lg"></i>&nbsp;
								<?php echo 'توسط: ' . $_author->full_name();
								if( ! empty($_author->photo)): ?>
									<img class="author-photo img-circle pull-left" alt="<?php echo $_author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($_author->photo); ?>"/>
								<?php endif; ?>
							<?php endif; ?>
						</h5>
						<h5>
							<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_shamsi($current_article->created_at)); ?>
						</h5>
						<h5>
							<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?>
						</h5>
						<?php if(check_ownership($current_article->author_id, $session->id)): ?>&nbsp;
							<a class="btn btn-primary btn-small" href="author_edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>">
								ویرایش
							</a>
						<?php endif; ?>
					</div>
					<div class="panel-body">
						<p><?php echo nl2br(strip_tags($current_article->content, ARTICLE_ALLOWABLE_TAGS)); ?></p>
					</div>
					<div class="panel-footer">
						<article id="comments">
							<?php include_layout_template('article-comments.php') ?>
						</article>
						<?php // include_layout_template('article-disqus-comment.php'); ?>
					</div>
				</div>

			<?php elseif($current_subject): ?>
				<?php if( ! $current_subject->visible) redirect_to('author_articles.php'); ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a class="btn btn-success btn-small arial" href="new_article.php?subject=<?php echo urlencode($current_subject->id); ?>" data-toggle="tooltip" title="مقاله جدید">
								<i class="fa fa-plus fa-lg"></i>
							</a> &nbsp;<?php echo htmlentities(ucwords($current_subject->name)); ?>&nbsp;
						</h2>
					</div>
					<div class="panel-body">
						<h4><i class="fa fa-newspaper-o"></i> مقالات در این موضوع: </h4><br>
						<ul>
							<?php $subject_articles = Article::find_articles_for_subject($current_subject->id, FALSE);
							foreach($subject_articles as $article):
								echo "<li class='list-group-item-text'>";
								$safe_article_id = urlencode($article->id);
								echo '<a href="author_articles.php?subject=' . $current_subject->id . '&article=' . $safe_article_id . '"';
								if($article->comments()):
									echo 'data-toggle="tooltip" data-placement="left" title="';
									echo convert(count($article->comments())) . ' دیدگاه';
									echo '"';
								endif;
								echo '>';
								echo htmlentities(ucwords($article->name));
								echo '</a>';
								echo '</li>';
							endforeach; ?>
						</ul>
					</div>
				</div>
			<?php else: ?>
				<?php include_layout_template('author-articles-info.php') ?>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و مقالات</h2>
			<?php echo articles($current_subject, $current_article); ?>
			<h2>موضوع جدید</h2>
			<p>نویسندگان عزیز اگر موضوع خاصی مد نظرتان هست که در موضوعات نیست، بدون هیچ تعارفی یک ایمیل کوتاه به نویسنده
			   بزنید، (از ایمیل نویسندگی تون) و اشاره کنید چه موضوعی می خواهید. مطالب شما حتما نباید زیر یکی از موضوعات بالا
			   قرار بگیرند.</p>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>