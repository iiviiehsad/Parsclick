<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - مقالات";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_article(TRUE);
$errors = "";
$body   = "";
if(isset($current_article->author_id)) { // find the author for the article
	$author = Author::find_by_id($current_article->author_id);
//}
//if(isset($_POST["submit"])) {
//	$member_id   = (int)$member->id;
//	$body        = trim($_POST["body"]);
//	$new_comment = ArticleComment::make($member_id, $current_article->id, $body);
//	if($new_comment && $new_comment->create()) {
//		$session->message("نظر شما با موفقیت فرستاده شد.");
//		redirect_to($_SERVER['HTTP_REFERER'] . "#comments");
//	} else {
//		$errors = "خطا در فرستادن نظر!";
//	}
} else {
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article id="member_article">
			<?php if($current_subject && $current_article) { ?>
				<?php
				// Pagination
				$page        = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
				$per_page    = 10;
				$total_count = ArticleComment::count_comments_for_article($current_article->id);
				$pagination  = new pagination($page, $per_page, $total_count);
				$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="text-success">
							<?php if(isset($author)) {
								if(empty($author->photo)) { ?>
									<i class="fa fa-user fa-lg"></i>&nbsp;
								<?php } else { ?>
									<img style="width:50px;height:50px;" class="img-responsive img-circle" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
								<?php }
								echo "توسط: " . $author->full_name();
							} ?>
						</h5>
						<h5 class="text-success">
							<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?>
						</h5>
						<h3 class="panel-title">
							<?php echo htmlentities($current_article->name); ?>
						</h3>
					</div>
					<div class="panel-body">
						<?php echo nl2br(strip_tags($current_article->content, '<h3><h4><strong><em><p><code><pre><mark><kbd><ul><ol><li><dl><dt><dd>')); ?>
					</div>
					<div class="panel-footer">
						<article id="comments">
							<div id="ajax-comments">
								<h2>نظرات</h2>
								<?php echo output_message($message); ?>
								<?php foreach($comments as $comment) { ?>
									<section class="media">
										<?php $_member = Member::find_by_id($comment->member_id); ?>
										<img class="img-circle pull-right" width="50" style="padding-right:0;" src="//www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->username; ?>">
										<div class="media-body">
											<span class="label label-as-badge label-success"><?php echo htmlentities($_member->first_name); ?></span>
											<span class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
											<?php if($comment->member_id === $session->id) { ?>
												<a href="member-delete-article-comment?id=<?php echo urlencode($comment->id); ?>" class="label label-as-badge label-danger" title="حذف" data-toggle="tooltip" onclick="return confirm('آیا مطمئن هستید؟')">
													<i class="fa fa-times"></i>
												</a>
											<?php } ?>
											<br/>
											<?php echo nl2br(strip_tags($comment->body, '<strong><em><p><pre>')); ?>
										</div>
									</section>
								<?php } // end foreach comments ?>
								<?php if($pagination->total_page() > 1) { ?>
									<nav class="clearfix center">
										<ul class="pagination">
											<?php if($pagination->has_previous_page()) { ?>
												<li>
													<a href="member-articles?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id); ?>&page=<?php echo urlencode($pagination->previous_page()); ?>#comments" aria-label="Previous">
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
														<a href="member-articles?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id); ?>&page=<?php echo urlencode($i); ?>#comments"><?php echo $i; ?></a>
													</li>
												<?php } ?>
											<?php } ?>
											<?php if($pagination->has_next_page()) { ?>
												<li>
													<a href="member-articles?subject=<?php echo urlencode($current_article->subject_id); ?>&article=<?php echo urlencode($current_article->id) ?>&page=<?php echo urlencode($pagination->next_page()); ?>#comments" aria-label="Next">
														<span aria-hidden="true">&gt;&gt;</span>
													</a>
												</li>
											<?php } // end: if($pagination->has_next_page()) ?>
										</ul>
									</nav>
								<?php } // end pagination?>
								<?php if(empty($comments)) { ?>
									<h3><span class="badge">نظری وجود ندارد. اولین نفری باشید که نظر می دهید.</span></h3>
								<?php } ?>
							</div>
							<br>
							<fieldset>
								<legend></legend>
								<form class="form-horizontal submit-comment" action="add-article-comment.php" method="POST" role="form">
									<!--content-->
									<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
										<img class="img-circle pull-left" width="100" style="padding-right:0;" src="//www.gravatar.com/avatar/<?php echo md5($member->email); ?>?s=100&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->username; ?>">
									</label>
									<div class="controls">
										<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" required placeholder=" نظرتان را اینجا وارد کنید و این تگ ها هم قابل استفاده اند <strong><pre><em><p>"></textarea>
									</div>
									<input type="hidden" name="article" value="<?php echo urlencode($current_article->id); ?>">
									<!--buttons-->
									<section class="row">
										<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
											<button class="btn btn-primary" name="submit" id="submit" type="submit">
												بفرست
											</button>
										</div>
									</section>
								</form>
							</fieldset>
						</article>
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