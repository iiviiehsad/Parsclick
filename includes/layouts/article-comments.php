<?php
global $session;
global $current_article;
// Pagination
$page        = ! empty($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page    = 10;
$total_count = ArticleComment::count_comments_for_article($current_article->id);
$pagination  = new pagination($page, $per_page, $total_count);
$comments    = ArticleComment::find_comments($current_article->id, $per_page, $pagination->offset());
?>
<?php if ( ! isset($session->id)): ?>
	<h4><span class="badge">برای اظهار نظر لطفا عضو شوید</span></h4>
<?php endif; ?>
<h3>
	<i class="fa fa-comments-o fa-2x"></i>
	<?php if ( ! empty($comments)): ?>
		<span class="label label-as-badge label-info"><?php echo convert(count($current_article->comments())); ?>
			نظر</span>
	<?php else: ?>
		<span class="label label-as-badge label-danger">نظری وجود ندارد</span>
	<?php endif; ?>
</h3>
<?php foreach ($comments as $comment): ?>
	<section class="media">
		<?php $_member = Member::find_by_id($comment->member_id); ?>
		<img class="img-circle pull-right" width="70" style="padding-right:0;"
		     src="https://www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=70&d=<?php echo 'https://' . DOMAIN .
				     '/images/misc/default-gravatar-pic.png'; ?>"
		     alt="<?php echo $_member->username; ?>">
		<div class="media-body">
			<a class="label label-as-badge label-success"
			   href="<?php echo is_local() ? '' : '/'; ?>profile?q=<?php echo htmlentities($_member->username); ?>">
				<?php echo htmlentities($_member->username); ?>
			</a>
			<span
					class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_shamsi($comment->created)); ?></span>
			<?php if (isset($session->id)): ?>
				<?php if ($comment->member_id === $session->id): ?>
					<a href="member-delete-article-comment?id=<?php echo urlencode($comment->id); ?>"
					   class="label label-as-badge label-danger confirmation" title="حذف" data-toggle="tooltip">
						<i class="fa fa-times"></i>
					</a>
				<?php endif; ?>
				<?php if ($session->is_admin_logged_in()): ?>
					<a class="badge label-danger"
					   href="admin_delete_article_comment.php?id=<?php echo urlencode($comment->id); ?>">
						<i class="fa fa-times"></i>
					</a>
				<?php endif; ?>
			<?php endif; ?>
			<br/>
			<?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?>
		</div>
	</section>
<?php endforeach; ?>
<?php echo paginate($pagination, $page, [
		'subject' => $current_article->subject_id,
		'article' => $current_article->id . '#comments',
]); ?>
