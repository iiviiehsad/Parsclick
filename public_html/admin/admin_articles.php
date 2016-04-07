<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
find_selected_article();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_subject && $current_article): ?>
				<h2><i class="fa fa-newspaper-o"></i> تنظیم مقاله </h2>
				<?php if(isset($current_article->author_id) && ! empty(Author::find_by_id($current_article->author_id)->photo)): ?>
					<img class="author-photo img-circle pull-left" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode(Author::find_by_id($current_article->author_id)->photo); ?>"/>
				<?php endif; ?>
				<dl class="dl-horizontal">
					<dt>اسم مقاله:</dt>
					<dd><?php echo htmlentities(ucwords($current_article->name)); ?></dd>
					<dt>محل:</dt>
					<dd><?php echo $current_article->position; ?></dd>
					<dt>نمایان:</dt>
					<dd><?php echo $current_article->visible == 1 ? 'بله' : 'خیر'; ?></dd>
					<dt>نویسنده:</dt>
					<dd><?php echo isset($current_article->author_id) ? htmlentities(Author::find_by_id($current_article->author_id)->full_name()) : '-'; ?></dd>
					<dt>تغییرات</dt>
					<dd>
						<a class="btn btn-primary btn-small arial" href="edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>" data-toggle="tooltip" title="ویرایش">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</dd>
					<dt>مطالب:</dt>
					<dd><?php echo nl2br(strip_tags($current_article->content, ARTICLE_ALLOWABLE_TAGS)); ?></dd>
				</dl>
				<hr>
				<?php echo output_message($message); ?>
				<article id="comments">
					<?php include_layout_template('article-comments.php') ?>
				</article>
				<?php // include_layout_template('article-disqus-comment.php'); ?>
			<?php elseif($current_subject): ?>
				<h2><i class="fa fa-list-alt"></i> تنظیم موضوع </h2>
				<dl class="dl-horizontal">
					<dt>اسم موضوع:</dt>
					<dd><?php echo htmlentities(ucwords($current_subject->name)); ?></dd>
					<dt>محل:</dt>
					<dd><?php echo $current_subject->position; ?></dd>
					<dt>نمایان:</dt>
					<dd><?php echo $current_subject->visible == 1 ? 'بله' : 'خیر'; ?></dd>
					<dt>&nbsp;</dt>
					<dd>
						<a class="btn btn-primary btn-small" href="edit_subject.php?subject=<?php echo urlencode($current_subject->id); ?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</dd>
				</dl>
				<?php if(Article::num_articles_for_subject($current_subject->id, FALSE)): ?>
					<hr>
					<div>
						<h2><i class="fa fa-newspaper-o"></i> مقالات در این موضوع </h2>
						<ul>
							<?php
							$subject_articles = Article::find_articles_for_subject($current_subject->id, FALSE);
							foreach($subject_articles as $article):
								echo '<li class="list-group-item-text">';
								echo '<a href="admin_articles.php?subject=' . $current_subject->id . '&article=' . urlencode($article->id) . '"';
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
				<?php endif; ?>
			<?php else: ?>
				<h2>لطفا یک مقاله یا یک موضوع انتخاب کنید.</h2>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و مقالات</h2>
			<a class="arial btn btn-success pull-left" href="new_subject.php" title="Add New Subject"><span class="glyphicon glyphicon-plus"></span></a>
			<?php echo admin_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>