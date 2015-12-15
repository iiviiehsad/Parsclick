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
				<?php
				$page        = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
				$per_page    = 10;
				$total_count = ArticleComment::count_comments_for_article($current_article->id);
				$pagination  = new pagination($page, $per_page, $total_count);
				$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
				?>
				<?php $_author = Author::find_by_id($current_article->author_id); ?>
				<h1>
					<?php echo $current_article->visible == 1 ? '<i class="fa fa-eye"></i>' : '<i class="text-danger fa fa-eye-slash"></i>'; ?>
					<?php echo htmlentities(ucwords($current_article->name)); ?>
				</h1>
				<h4 class="text-success">
					<?php if(! empty($_author->photo)) { ?>
						<img class="img-circle" width="50" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($_author->photo); ?>">
					<?php } ?>
					<?php echo isset($current_article->author_id) ? htmlentities($_author->full_name()) : "-"; ?>
				</h4>
				<?php if(check_ownership($current_article->author_id, $session->id)) { ?>&nbsp;
					<a class="btn btn-primary btn-small" href="author_edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>">
						ویرایش
					</a>
				<?php } ?>
				<p><?php echo nl2br(strip_tags($current_article->content, '<h3><h4><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd>')); ?></p>
				<article id="comments">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="panel-title"><i class="fa fa-comments-o"></i> نظرات</h2>
						</div>
						<div class="panel-body">
							<?php foreach($comments as $comment) { ?>
								<section class="media">
									<?php $_member = Member::find_by_id($comment->member_id); ?>
									<img class="img-circle pull-right" width="50" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo DOMAIN . DS . 'images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->email; ?>">
									<div class="media-body">
										<span class="badge"><?php echo htmlentities($_member->first_name); ?></span>
										<span class="badge"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
										<br/>
										<?php echo strip_tags($comment->body, '<strong><em><p><pre>'); ?>
									</div>
								</section>
							<?php } // end foreach comments ?>
							<?php if($pagination->total_page() > 1) { ?>
								<nav class="clearfix center">
									<ul class="pagination">
										<?php if($pagination->has_previous_page()) { ?>
											<li>
												<a href="author_articles.php?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id); ?>&page=<?php echo urlencode($pagination->previous_page()); ?>#comments" aria-label="Previous">
													<span aria-hidden="true"> &lt;&lt; </span>
												</a>
											</li>
										<?php } // end: if($pagination->has_previous_page()) ?>
										<?php for($i = 1; $i < $pagination->total_page() + 1; $i ++) { ?>
											<?php if($i == $page) { ?>
												<li class="active">
													<span><?php echo $i; ?></span>
												</li>
											<?php } else { ?>
												<li>
													<a href="author_articles.php?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id); ?>&page=<?php echo urlencode($i); ?>#comments"><?php echo $i; ?></a>
												</li>
											<?php } ?>
										<?php } ?>
										<?php if($pagination->has_next_page()) { ?>
											<li>
												<a href="author_articles.php?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id) ?>&page=<?php echo urlencode($pagination->next_page()); ?>#comments" aria-label="Next">
													<span aria-hidden="true">&gt;&gt;</span>
												</a>
											</li>
										<?php } // end: if($pagination->has_next_page()) ?>
									</ul>
								</nav>
							<?php } // end pagination?>
							<?php if(empty($comments)) { ?>
								<h3><span class="badge">نظری وجود ندارد.</span></h3>
							<?php } ?>
						</div>
					</div>
				</article>
			<?php } elseif($current_subject) { ?>
				<?php if(! $current_subject->visible) redirect_to("author_articles.php"); ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a class="btn btn-success btn-small arial" href="new_article.php?subject=<?php echo urlencode($current_subject->id); ?>">
								<i class="fa fa-plus fa-lg"></i>
							</a> &nbsp;<?php echo htmlentities(ucwords($current_subject->name)); ?>&nbsp;
						</h2>
					</div>
					<div class="panel-body">
						<h4><i class="fa fa-newspaper-o"></i> مقالات در این موضوع: </h4><br>
						<ul>
							<?php
							$subject_articles = Article::find_articles_for_subject($current_subject->id, FALSE);
							foreach($subject_articles as $article) {
								echo "<li class='list-group-item-text'>";
								$safe_article_id = urlencode($article->id);
								echo "<a href='author_articles.php?subject=" . $current_subject->id . "&article={$safe_article_id}'";
								if($article->comments()) {
									echo "data-toggle='tooltip' data-placement='left' title='";
									echo count($article->comments()) . " دیدگاه";
									echo "'";
								}
								echo ">";
								if(empty($article->name)) {
									echo "(اسم مقاله موجود نیست)";
								}
								echo htmlentities(ucwords($article->name));
								echo "</a>";
								echo "</li>";
							} ?>
						</ul>
					</div>
				</div>
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
					<li>
						<p>قبل از گذاشتن مقاله درون ویرایشگر متن:</p>
						<ul>
							<li>دور کلمات انگلیسی از تگ <code>&lt;code&gt;</code> استفاده کنید</li>
							<li>دور کد های چند خطه از تگ <code>&lt;pre&gt;</code> استفاده کنید</li>
							<li>اگر لیست اضافه می کنید از تگ های زیر استفاده کنید:
								<pre>&lt;ul&gt;<br> &lt;li&gt;لیست اول&lt;/li&gt;<br> &lt;li&gt;لیست دوم&lt;/li&gt;<br>&lt;/ul&gt;</pre>
							</li>
							<li>تگ های <code>HTML</code> قابل رویت نیست یعنی وقتی مطلبی رو می سازید که می خواهید کدهای
								<code>HTML</code> رو نشون بدید این کدها دیده نخواهند شد چون کدهای <code>HTML</code> تبدیل میشوند. برای
							    نشون دادن این کدها باید <code>&lt;&gt;</code> ها را به این صورت تبدیل کنید:
								<pre>&lt;html&gt; => <span class="text-danger">&amp;lt;</span>html<span class="text-danger">&amp;gt;</span><br>&lt;/html&gt; => <span class="text-danger">&amp;lt;</span>/html<span class="text-danger">&amp;gt;</span></pre>
						</ul>
					</li>
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