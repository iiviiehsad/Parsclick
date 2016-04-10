<?php
global $session;
global $current_article;
global $current_subject;
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<?php if($session->is_author_logged_in() && check_ownership($current_article->author_id, $session->id)): ?>&nbsp;
			<a class="btn btn-primary" href="author_edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>">
				ویرایش
			</a>
		<?php endif; ?>
		<?php if($session->is_admin_logged_in()): ?>
			<a class="btn btn-primary" href="edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>" data-toggle="tooltip" title="ویرایش">
				ویرایش
			</a>
		<?php endif ?>
		<h3>
			<?php echo htmlentities(ucwords($current_article->name)); ?>
		</h3>
		<h5>
			<i class="fa fa-calendar"></i>&nbsp;
			<?php echo htmlentities(datetime_to_text($current_article->created_at)); ?>
		</h5>
		<h5>
			<i class="fa fa-calendar"></i>&nbsp;
			<?php echo datetime_to_shamsi($current_article->created_at); ?>
		</h5>
		<h5>
			<?php if(isset($current_article->author_id)): ?>
				<?php $_author = Author::find_by_id($current_article->author_id); ?>
				<i class="fa fa-user fa-lg"></i>&nbsp;
				<?php echo $_author->full_name();
				if( ! empty($_author->photo)): ?>
					<img class="author-photo img-circle pull-left" alt="<?php echo $_author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($_author->photo); ?>"/>
				<?php endif; ?>
			<?php endif; ?>
		</h5>
		<?php if($session->is_admin_logged_in() || $session->is_author_logged_in()): ?>
			<h5><i class="fa fa-eye fa-lg"></i>&nbsp;
				<?php echo $current_article->visible == 1 ? '<span class="text-success">بله</span>' : '<span class="text-danger">خیر</span>'; ?>
			</h5>
			<h5><i class="fa fa-list-ol fa-lg"></i>&nbsp;
				<?php echo convert($current_article->position); ?>
			</h5>
		<?php endif ?>
	</div>
	<div class="panel-body">
		<?php echo nl2br(strip_tags($current_article->content, ARTICLE_ALLOWABLE_TAGS)); ?>
	</div>
	<div class="panel-footer">
		<article id="comments">
			<?php if($session->is_logged_in()): ?>
				<?php global $member; ?>
				<form class="form-horizontal submit-comment" action="add-article-comment.php" method="POST" role="form">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
						<img class="img-circle pull-left hidden-sm" width="100" style="padding-right:0;" src="//www.gravatar.com/avatar/<?php echo md5($member->email); ?>?s=100&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->username; ?>">
					</label>
					<div class="controls">
						<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" required placeholder=" نظرتان را اینجا وارد کنید و این تگ ها هم قابل استفاده اند <strong><pre><em><p>"></textarea>
					</div>
					<input type="hidden" name="article" value="<?php echo urlencode($current_article->id); ?>">
					<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
						<button class="btn btn-primary" name="submit" id="submit" type="submit">
							بفرست
						</button>
					</div>
				</form>
			<?php endif; ?>
			<div id="forum">
				<div id="ajax-comments">
					<?php include_layout_template('article-comments.php') ?>
				</div>
			</div>
		</article>
	</div>
</div>
