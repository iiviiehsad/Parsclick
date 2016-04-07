<?php
require_once('../includes/initialize.php');
$session->confirm_logged_in();
$filename = basename(__FILE__);
$title    = 'پارس کلیک - جستجوی مقالات';
$member   = Member::find_by_id($session->id);
$member->check_status();
find_selected_article(TRUE);
$search_query = trim($_GET['q']);
if(isset($search_query) && ! empty($search_query)) {
	$article_set = Article::search($search_query);
} else { // this is a $_GET request
	$session->message('شما چیزی جستجو نکردید.');
	redirect_to('member-articles');
}
?>
<?php include_layout_template('header.php'); ?>
<?php include('_/components/php/member_nav.php'); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if( ! empty($article_set)): ?>
				<h2>نتیجه جستجو</h2>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>مقالات پیدا شده: <span class="badge"><?php echo convert(count($article_set)); ?></span></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($article_set as $article): ?>
								<tr>
									<td>
										<strong>
											<i>
												<a href="member-articles?subject=<?php echo urlencode($article->subject_id); ?>&article=<?php echo urlencode($article->id); ?>">
													<mark><?php echo htmlentities($article->name); ?></mark>
													<small>
														&nbsp;توسط <?php echo isset($article->author_id) ? htmlentities(Author::find_by_id($article->author_id)
														                                                                      ->full_name()) : '-'; ?></small>
												</a>
											</i>
										</strong>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="center">
					<h3>برای <?php echo $search_query; ?></h3>
					<h1>چیزی پیدا نشد!</h1>
					<h1><i class="fa fa-frown-o fa-4x"></i></h1>
				</div>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="member-article-search" method="get">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی مقالات"/>
				</div>
			</form>
			<h2>موضوعات</h2>
			<?php echo member_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template('footer.php'); ?>