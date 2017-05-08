<?php
global $session;
global $current_course;
# Pagination
$page        = ! empty($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page    = 30;
$total_count = Comment::count_comments_for_course($current_course->id);
$pagination  = new pagination($page, $per_page, $total_count);
$comments    = Comment::find_comments($current_course->id, $per_page, $pagination->offset());
?>
<?php if (empty($comments)): ?>
	<h3>
		<i class="fa fa-comments-o fa-2x"></i>
		<span class="label label-as-badge label-danger">دیدگاهی وجود ندارد</span>
	</h3>
<?php else: ?>
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="table-responsive">
				<?php foreach ($comments as $comment): ?>
					<section class="media">
						<?php $_member = Member::find_by_id($comment->member_id); ?>
						<img class="img-circle pull-right" width="70"
						     src="https://www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=70&d=<?php echo 'https://' .
							     DOMAIN .
							     '/images/misc/default-gravatar-pic.png'; ?>"
						     alt="<?php echo $_member->username; ?>">
						<div class="media-body">
							<a class="arial label label-as-badge label-success"
							   href="<?php echo is_local() ? '' : '/'; ?>profile?q=<?php echo htmlentities($_member->username); ?>">
								<?php echo htmlentities($_member->username); ?>
							</a>
							<span class="label label-as-badge label-info">
								<?php echo htmlentities(datetime_to_shamsi($comment->created)); ?>
							</span>
							<?php if (isset($session->id)): ?>
								<?php if ($comment->member_id === $session->id): ?>
									<a href="member-delete-comment?id=<?php echo urlencode($comment->id); ?>"
									   class="label label-as-badge label-danger confirmation">
										<i class="fa fa-times"></i>
									</a>
								<?php endif; ?>
								<?php if ($session->is_admin_logged_in()): ?>
									<a class="label label-as-badge label-danger"
									   href="admin_delete_comment.php?id=<?php echo urlencode($comment->id); ?>">
										<i class="fa fa-times"></i>
									</a>
								<?php endif; ?>
							<?php endif; ?>
							<br/>
							<?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?>
						</div>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="panel-footer">
			<?php echo paginate($pagination, $page, array_merge([
				'category' => $current_course->category_id,
				'course'   => $current_course->id,
			], get_prev_next_token())); ?>
		</div>
	</div>
<?php endif; ?>

