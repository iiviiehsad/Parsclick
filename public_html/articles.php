<?php
require_once("../includes/initialize.php");
if($session->is_logged_in()) {
	redirect_to("member.php");
}
$filename = basename(__FILE__);
find_selected_article(TRUE);
$errors = "";
$body   = "";
if(isset($current_article->author_id)) {
	$author = Author::find_by_id($current_article->author_id);
}
if(isset($current_article)) {
	$title = "پارس کلیک - " . $current_article->name;
} else {
	$title = "پارس کلیک - مقالات و اخبار";
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/nav.php"); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article id="member_article">
		<?php if($current_subject && $current_article) { ?>
			<h2><?php echo htmlentities($current_article->name); ?></h2>
			<h5>
				<?php if(isset($author)) { ?>
					<i class="fa fa-user fa-lg"></i>&nbsp;
					<?php echo "توسط: " . $author->full_name();
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
				<h3><span class="badge">برای اظهار نظر و دیدن نظرات لطفا عضو شوید.</span></h3>
			</article>
		<?php } else { ?>
			<?php $newest_article = Article::find_newest_article(); ?>
			<h2 class="text-danger">آخرین مقاله:</h2>
			<h3><?php echo htmlentities($newest_article->name); ?></h3>
			<h5>
				<?php
				if(isset($newest_article->author_id)) {
					$author = Author::find_by_id($newest_article->author_id);
					?><i class="fa fa-user fa-lg"></i>&nbsp;
					<?php echo "توسط: " . $author->full_name();
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
		<?php include("_/components/php/aside-read.php"); ?>
	</aside>
</section>
<?php include_layout_template("footer.php"); ?>
