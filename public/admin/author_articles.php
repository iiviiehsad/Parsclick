<?php
require_once("../../includes/initialize.php");
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
$filename = basename(__FILE__);
find_selected_article();
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_subject && $current_article) { ?>
				<h1>
					<?php echo $current_article->visible ==
					           1 ? '<i class="fa fa-eye"></i>' : '<i class="text-danger fa fa-eye-slash"></i>'; ?>
					&nbsp;
					<?php echo htmlentities(ucwords($current_article->name)); ?>
				</h1>
				<h4 class="text-success">
					نویسنده:
					<?php echo isset($current_article->author_id) ? htmlentities(Author::find_by_id($current_article->author_id)
					                                                                   ->full_name()) : "-"; ?>
				</h4>
				<?php if(check_ownership($current_article->author_id, $session->id)) { ?>&nbsp;
					<a class="btn btn-primary btn-small" href="author_edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>">
						ویرایش
					</a>
				<?php } ?>
				<p><?php echo nl2br(strip_tags($current_article->content,
				                               '<h1><h2><h3><h4><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd>')); ?></p>
			<?php } elseif($current_subject) { ?>
				<?php if(!$current_subject->visible) redirect_to("author_articles.php"); ?>
				<h2>
					<a class="btn btn-success btn-small arial" href="new_article.php?subject=<?php echo urlencode($current_subject->id); ?>">
						<i class="fa fa-plus"></i>
					</a>
					&nbsp;<?php echo htmlentities(ucwords($current_subject->name)); ?>&nbsp;
				</h2>
				<br/>
				<h4><i class="fa fa-newspaper-o"></i> مقالات در این موضوع: </h4>
				<ul>
					<?php
					$subject_articles = Article::find_articles_for_subject($current_subject->id, FALSE);
					foreach($subject_articles as $article) {
						echo "<li class='list-group-item-text'>";
						$safe_article_id = urlencode($article->id);
						echo "<a href='author_articles.php?subject=" . $current_subject->id . "&article={$safe_article_id}'>";
						if(empty($article->name)) {
							echo "(اسم مقاله موجود نیست)";
						}
						echo htmlentities(ucwords($article->name));
						echo "</a>";
						echo "</li>";
					} ?>
				</ul>
			<?php } else { ?>
				<h2>لطفا یک موضوع یا مقاله انتخاب کنید.</h2>
				<p class="lead text-danger"><i class="fa fa-info-circle"></i> نکات مهم:</p>
				<ul>
					<li><p>به عنوان یک نویسنده شما مسئول ساختن، بروزرساندن، و پاک کردن مقاله های خود هستید.</p></li>
					<li><p>مقاله هایی که توسط شما نوشته می شوند، توسط مدیران ویرایش و تنظیم خواهند شد.</p></li>
					<li><p>در کنار اسم هر مقاله، آیکانی به شکل چشم وجود دارد که نشاندهنده ی این است که مقاله نشر شده یا
					       نشده است. مقاله هایی که توسط شما ساخته می شوند، تا زمان تنظیم و ویرایش آنها توسط مدیران قابل
					       دیدن نمی باشند.</p></li>
					<li><p>غلط املایی یکی از آشکارترین چیزهاست که نشان میدهد شما مقالات خود را چک نکردید. پس مواظب باشید ما غلط
					       املایی نمی خواهیم این را جدا عرض کردم. یک لغت اگر بیشتر از دوبار در هر مقاله ای غلط بود این یعنی مقاله
					       شما کپی است و ارزش ندارد.این مقاله شما را نشر نخواهد کرد.</p></li>
					<li><p>لطفا سعی بر پاک کردن مقاله هایی که از قبل توسط مدیران تنظیم شده اند ننمائید مگر اینکه مایل به
					       بروزرساندن آنها هستید.</p></li>
					<li><p>پاک کردن مقاله ای بدون دلیل باعث معلق شدن عضویت شما به عنوان نویسنده خواهد شد.</p></li>
				</ul>
			<?php } ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و مقالات</h2>
			<?php echo author_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>