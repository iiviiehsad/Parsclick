<?php require_once('../includes/initialize.php');
if ($session->is_logged_in()) redirect_to('member-articles?' . $_SERVER['QUERY_STRING']);
find_selected_article(TRUE);
$newest_article = Article::find_newest_article();
$errors         = '';
$body           = '';
if (isset($current_article->author_id)) $author = Author::find_by_id($current_article->author_id);
$title = isset($current_article) ? 'پارس کلیک - ' . $current_article->name : 'پارس کلیک - ' . $newest_article->name;
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article id="member_article">
		<?php if ($current_subject && $current_article): ?>
			<?php include_layout_template('article-info.php'); ?>
		<?php else: ?>
			<?php include_layout_template('recent-article.php'); ?>
		<?php endif; ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="members_menu">
		<?php include_layout_template('aside-share.php'); ?>
		<h2>موضوعات و مقالات</h2>
		<?php echo articles($current_subject, $current_article, TRUE); ?>
		<?php include_layout_template('weblog.php'); ?>
		<?php include_layout_template('aside-read.php'); ?>
		<?php include_layout_template('aside-ad.php'); ?>
	</aside>
</section>
<?php include_layout_template('footer.php'); ?>
