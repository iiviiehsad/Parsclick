<?php require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
find_selected_article();
// Pagination
$page        = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
$per_page    = 10;
$total_count = ArticleComment::count_comments_for_article($current_article->id);
$pagination  = new pagination($page, $per_page, $total_count);
$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
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
				<hr><?php echo output_message($message); ?>
				<article id="comments">
					<h3><?php echo convert(count($current_article->comments())); ?> نظر </h3>
					<?php foreach($comments as $comment): ?>
						<section class="media">
							<?php $_member = Member::find_by_id($comment->member_id); ?>
							<img class="img-circle pull-right" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50" alt="<?php echo $_member->email; ?>">
							<div class="media-body arial">
						<span class="badge">
							<span class="yekan"><?php echo htmlentities($_member->full_name()); ?></span>
							<?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
								<a class="badge label-danger" href="admin_delete_article_comment.php?id=<?php echo urlencode($comment->id); ?>">
									<i class="fa fa-times"></i>
								</a>
								<p><?php echo strip_tags($comment->body, '<strong><em><p><pre>'); ?></p>
							</div>
						</section>
					<?php endforeach; ?>
					<?php echo paginate($pagination, $page, "admin_articles.php", "subject={$current_subject->id}", "&article={$current_article->id}#comments"); ?>
					<?php if(empty($comments)): ?>
						<h3><span class="label label-default">نظری نیست</span></h3>
					<?php endif; ?>
				</article>
				<?php //include('../_/components/php/article-disqus-comment.php'); ?>
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
								echo "<li class='list-group-item-text'>";
								$safe_article_id = urlencode($article->id);
								echo "<a href='admin_articles.php?subject=" . $current_subject->id . "&article={$safe_article_id}'";
								if($article->comments()):
									echo "data-toggle='tooltip' data-placement='left' title='";
									echo count($article->comments()) . " دیدگاه";
									echo "'";
								endif;
								echo ">";
								echo htmlentities(ucwords($article->name));
								echo "</a>";
								echo "</li>";
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
<?php include_layout_template("admin_footer.php"); ?>