<?php
require_once("../includes/initialize.php");
$session->confirm_logged_in();
$filename = basename(__FILE__);
$title    = "پارس کلیک - جستجوی مقالات";
$member   = Member::find_by_id($session->id);
$member->check_status();
find_selected_article(TRUE);
if(isset($_GET["q"]) && !empty($_GET["q"]) && $_GET["q"] != " ") {
	$article_set = Article::search($_GET["q"]);
} else { // this is a $_GET request
	$session->message("شما چیزی جستجو نکردید.");
	redirect_to("member-articles");
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>نتیجه جستجو</h2>
			<?php if(!empty($article_set)) { ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
						<tr>
							<th>مقالات پیدا شده: <span class="badge"><?php echo count($article_set); ?></span></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($article_set as $article): ?>
							<tr>
								<td>
									<a href="member-articles?subject=<?php echo urldecode($article->subject_id); ?>&article=<?php echo urldecode($article->id); ?>">
										<?php echo htmlentities($article->name); ?>
										&nbsp;توسط <?php echo isset($article->author_id) ? htmlentities(Author::find_by_id($article->author_id)->full_name()) : '-'; ?>
									</a>
									<p>
										<small>برای ادامه کلیک کنید...</small>
									</p>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php } else {
				$session->message("نتیجه ای پیدا نشد. لطفا دوباره بگردید.");
				redirect_to("member-articles");
			} ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="member-article-search" method="get">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="search" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی مقالات"/>
				</div>
			</form>
			<h2>موضوعات</h2>
			<?php echo member_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("footer.php"); ?>