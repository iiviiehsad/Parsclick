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
			<?php if($current_subject && $current_article): ?>
				<?php
				$page        = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
				$per_page    = 10;
				$total_count = ArticleComment::count_comments_for_article($current_article->id);
				$pagination  = new pagination($page, $per_page, $total_count);
				$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
				?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo $current_article->visible == 1 ? '<i class="fa fa-eye"></i>' : '<i class="text-danger fa fa-eye-slash"></i>'; ?>
							<?php echo htmlentities(ucwords($current_article->name)); ?>
						</h3>
						<h5>
							<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?>
						</h5>
						<h5>
							<?php if(isset($current_article->author_id)): ?>
								<?php $_author = Author::find_by_id($current_article->author_id); ?>
								<i class="fa fa-user fa-lg"></i>&nbsp;
								<?php echo "توسط: " . $_author->full_name();
								if( ! empty($_author->photo)): ?>
									<img class="author-photo img-circle pull-left" alt="<?php echo $_author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($_author->photo); ?>"/>
								<?php endif; ?>
							<?php endif; ?>
						</h5>
						<?php if(check_ownership($current_article->author_id, $session->id)): ?>&nbsp;
							<a class="btn btn-primary btn-small" href="author_edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>">
								ویرایش
							</a>
						<?php endif; ?>
					</div>
					<div class="panel-body">
						<p><?php echo nl2br(strip_tags($current_article->content, ARTICLE_ALLOWABLE_TAGS)); ?></p>
					</div>
					<div class="panel-footer">
						<article id="comments">
							<h2><i class="fa fa-comments-o"></i> نظرات</h2>
							<?php foreach($comments as $comment): ?>
								<section class="media">
									<?php $_member = Member::find_by_id($comment->member_id); ?>
									<img class="img-circle pull-right" width="50" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo DOMAIN . DS . 'images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->email; ?>">
									<div class="media-body">
										<span class="badge"><?php echo htmlentities($_member->first_name); ?></span>
										<span class="badge"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
										<br/>
										<?php echo nl2br(strip_tags($comment->body, '<strong><em><p><pre>')); ?>
									</div>
								</section>
							<?php endforeach; ?>
							<?php echo paginate($pagination, $page, "author_articles.php", "subject={$current_article->subject_id}", "&article={$current_article->id}#comments"); ?>
							<?php if(empty($comments)): ?>
								<h3><span class="badge">نظری وجود ندارد.</span></h3>
							<?php endif; ?>
						</article>
					</div>
				</div>
			<?php elseif($current_subject): ?>
				<?php if( ! $current_subject->visible) redirect_to("author_articles.php"); ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a class="btn btn-success btn-small arial" href="new_article.php?subject=<?php echo urlencode($current_subject->id); ?>" data-toggle="tooltip" title="مقاله جدید">
								<i class="fa fa-plus fa-lg"></i>
							</a> &nbsp;<?php echo htmlentities(ucwords($current_subject->name)); ?>&nbsp;
						</h2>
					</div>
					<div class="panel-body">
						<h4><i class="fa fa-newspaper-o"></i> مقالات در این موضوع: </h4><br>
						<ul>
							<?php
							$subject_articles = Article::find_articles_for_subject($current_subject->id, FALSE);
							foreach($subject_articles as $article):
								echo "<li class='list-group-item-text'>";
								$safe_article_id = urlencode($article->id);
								echo "<a href='author_articles.php?subject=" . $current_subject->id . "&article={$safe_article_id}'";
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
				</div>
			<?php else: ?>
				<h2>لطفا یک موضوع یا مقاله انتخاب کنید.</h2>
				<em class="text-success">همیشه به این صفحه قبل از انتشار مقاله نگاه کنید چون این صفحه به مراتب بروزرسانی می شود
				                         و یک جورایی ارتباط ما با نویسندگان در مورد ساخت مقاله هست.</em>
				<p class="lead text-danger"><i class="fa fa-info-circle"></i> نکات مهم:</p>
				<ul>
					<li><p>به عنوان یک نویسنده شما مسئول ساختن، بروزرساندن، و پاک کردن مقاله های خود هستید.</p></li>
					<li><p>مقاله هایی که توسط شما نوشته می شوند، توسط مدیران ویرایش و تنظیم خواهند شد.</p></li>
					<li><p>در کنار اسم هر مقاله، آیکانی به شکل چشم وجود دارد که نشاندهنده ی این است که مقاله نشر شده یا
					       نشده است. مقاله هایی که توسط شما ساخته می شوند، تا زمان تنظیم و ویرایش آنها توسط مدیران قابل
					       دیدن نمی باشند.</p></li>
					<li><p>غلط املایی یکی از آشکارترین چیزهاست که نشان میدهد شما مقالات خود را چک نکردید. پس مواظب باشید ما غلط
					       املایی نمی خواهیم این را جدا عرض کردم. یک لغت اگر بیشتر از دوبار در هر مقاله ای غلط بود این یعنی
					       مقاله
					       شما کپی است و ارزش ندارد.این مقاله شما را نشر نخواهد کرد.</p></li>
					<li>
						<p>برای آپلود کردن عکس چون بعضی ها میگن پیکاسا در ایران فیلتر هست، می تونید از
							<a href="http://imgur.com/" target="_blank" title="imageur">
								<mark>imageur</mark>
							</a> و یا <a href="https://app.prntscr.com/" target="_blank" title="lightshot">
								<mark>lightshot</mark>
							</a> استفاده کنید که lightshot سریعتر هم هست چون براش اپ وجود داره.
						</p>
					</li>
					<li>
						<p>قبل از گذاشتن مقاله درون ویرایشگر متن:</p>
						<ul>
							<li>دور کلمات انگلیسی از تگ <code>&lt;code&gt;</code> استفاده کنید</li>
							<li>دور کد های چند خطه از تگ <code>&lt;pre&gt;</code> استفاده کنید</li>
							<li>اگر لیست اضافه می کنید از تگ های زیر استفاده کنید:
								<pre class="line-numbers"><code class="language-html">&lt;ul&gt;<br>&lt;li&gt;لیست اول&lt;/li&gt;&lt;li&gt;لیست دوم&lt;/li&gt;&lt;/ul&gt;</code></pre>
							</li>
							<li>تگ های <code>HTML</code> قابل رویت نیست یعنی وقتی مطلبی رو می سازید که می خواهید کدهای
								<code>HTML</code> رو نشون بدید این کدها دیده نخواهند شد چون کدهای <code>HTML</code> تبدیل میشوند. برای
							    نشون دادن این کدها باید <code>&lt;&gt;</code> ها را به این صورت تبدیل کنید:
								<pre class="line-numbers"><code class="language-html">&lt;html&gt; => &amp;lt;html&amp;gt; &lt;/html&gt; => &amp;lt;/html&amp;gt;</code></pre>
							</li>
							<li>برای رنگ گرفتن کد های شما ما از <a href="http://prismjs.com/" title="prism" target="_blank">
									<mark>prism</mark>
								</a> استفاده میکنیم. استفاده از <code>prism</code> اینطور هست که کدهاتون رو داخل تگ <code>pre</code> و
							    بعد داخل تگ <code>code</code> قرار میدهید و به تگ کد کلاس <code>language-{html}</code> میدهید و به تگ
								<code>pre</code> کلاس <code>line-numbers</code> می دهید. توجه داشته باشید که به جای <code>{html}</code>
							    می تونید از هر زبانی دیگر استفاده کنید. مثال زیر مثالی هست که معمولا ما از آن استفاده می کنیم
								<pre class="line-numbers"><code class="language-html">&lt;pre class="line-numbers"&gt;&lt;code class="language-php"&gt; کدهای شما &lt;/code&gt;&lt;/pre&gt;</code></pre>
							</li>
						</ul>
					</li>
					<li><p>لطفا سعی بر پاک کردن مقاله هایی که از قبل توسط مدیران تنظیم شده اند ننمائید مگر اینکه مایل به
					       بروزرساندن آنها هستید.</p></li>
					<li><p>اگر نویسنده قدیمی هستید از شما انتظار می رود بعد از مدتی ویرایش کردن را بلد باشید. اگر ویرایش نکنید
					       مقاله شما تا دیده شدن توسط عموم طول خواهد کشید.</p></li>
				</ul>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و مقالات</h2>
			<?php echo author_articles($current_subject, $current_article); ?>
			<h2>موضوع جدید</h2>
			<p>نویسندگان عزیز اگر موضوع خاصی مد نظرتان هست که در موضوعات نیست، بدون هیچ تعارفی یک ایمیل کوتاه به نویسنده
			   بزنید، (از ایمیل نویسندگی تون) و اشاره کنید چه موضوعی می خواهید. مطالب شما حتما نباید زیر یکی از موضوعات بالا
			   قرار بگیرند.</p>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>