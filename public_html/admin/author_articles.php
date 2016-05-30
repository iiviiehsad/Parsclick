<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
find_selected_article();
include_layout_template('admin_header.php');
include_layout_template('author_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if ($current_subject && $current_article): ?>
				<?php include_layout_template('article-info.php'); ?>
			<?php elseif ($current_subject): ?>
				<?php if ( ! $current_subject->visible) redirect_to('author_articles.php'); ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a class="btn btn-info btn-small arial"
							   href="new_article.php?subject=<?php echo urlencode($current_subject->id); ?>" data-toggle="tooltip"
							   title="مقاله جدید">
								<i class="fa fa-plus fa-lg"></i>
							</a>
							<?php echo htmlentities(ucwords($current_subject->name)); ?>
						</h2>
					</div>
					<div class="panel-body">
						<?php include_layout_template('article-under-subject.php'); ?>
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