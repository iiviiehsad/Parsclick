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
		<article id="member_article">
			<?php if($current_subject && $current_article) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="text-success">
							<?php if(isset($author)) {
								if(empty($author->photo)) { ?>
									<i class="fa fa-user fa-lg"></i>&nbsp;
								<?php } else { ?>
									<img style="width:50px;height:50px;" class="img-responsive img-rounded" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
								<?php }
								echo "توسط: " . $author->full_name();
							} ?>
						</h5>
						<h5 class="text-success"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?></h5>
						<h3 class="panel-title">
							<?php echo htmlentities($current_article->name); ?>
						</h3>
					</div>
					<div class="panel-body">
						<?php echo nl2br(strip_tags($current_article->content, '<h3><h4><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd>')); ?>
					</div>
					<div class="panel-footer">
						<?php include("_/components/php/article-disqus-comment.php"); ?>
					</div>
				</div>
			<?php } else { ?>
				<?php include_once("_/components/php/member_article_info.php"); ?>
			<?php } ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
			<form class="form-inline" action="member-article-search" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="arial glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="30" maxlength="50" data-toggle="tooltip" data-placement="top" title="جستجو کنید و اینتر بزنید" placeholder="جستجوی مقالات"/>
				</div>
			</form>
			<h2>موضوعات و مقالات</h2>
			<?php echo member_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("footer.php"); ?>