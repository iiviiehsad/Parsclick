<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - مقالات";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_article(TRUE);
if(isset($current_article->author_id)) { // find the author for the article
	$author = Author::find_by_id($current_article->author_id);
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article class="member_profile">
			<?php if($current_subject && $current_article) { ?>
				<h2><?php echo htmlentities($current_article->name); ?></h2>
				<h4 class="text-success">
					<?php if(isset($author)) {
						if(empty($author->photo)) { ?>
							<i class="fa fa-user fa-lg"></i>
						<?php } else { ?>
							<img style="width:50px;height:50px;" class="img-responsive img-rounded" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
						<?php }
						echo $author->full_name();
					} ?>
				</h4>
				<p><?php echo nl2br(strip_tags($current_article->content,
				                               '<h1><h2><h3><h4><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd>')); ?></p>
				<!--  COMMENTS -->
				<hr/>
				<?php include("_/components/php/article-disqus-comment.php"); ?>
			<?php } else { ?>
				<?php include_once("_/components/php/member_article_info.php"); ?>
			<?php } ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
			<form class="form-inline" action="member-article-search" method="get">
				<div class="input-group">
					<span class="input-group-addon"><span class="edit glyphicon glyphicon-search"></span></span>
					<input type="search" name="q" class="form-control" size="30" maxlength="50" placeholder="جستجوی مقالات"/>
				</div>
			</form>
			<h2>موضوعات و مقالات</h2>
			<?php echo member_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("footer.php"); ?>