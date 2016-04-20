<?php require_once('../includes/initialize.php'); ?>
<?php $session->confirm_logged_in(); ?>
<?php $filename = basename(__FILE__);
$title          = 'پارس کلیک - جستجوی بین انجمن';
$member         = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$search_query = trim($_GET['q']);
if(isset($search_query) && ! empty($search_query)) {
	$comment_set = Comment::search($search_query);
} else { // this is a $_GET request
	$session->message('شما چیزی جستجو نکردید.');
	redirect_to('forum');
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if( ! empty($comment_set)): ?>
				<h2>نتیجه جستجو</h2>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>موضوع های پیدا شده: <span class="badge"><?php echo convert(count($comment_set)); ?></span></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($comment_set as $comment): ?>
								<tr>
									<td>
										<section class="media">
											<?php $_member = Member::find_by_id($comment->member_id); ?>
											<img class="img-circle pull-right" width="50" style="padding-right:0;"
											     src="//www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>"
											     alt="<?php echo $_member->username; ?>">
											<div class="media-body">
												<span
														class="label label-as-badge label-success"><?php echo htmlentities($_member->first_name); ?></span>
												<span
														class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_shamsi($comment->created)); ?></span>
												<?php if(isset($session->id)): ?>
													<?php if($comment->member_id === $session->id): ?>
														<a href="member-delete-comment?id=<?php echo urlencode($comment->id); ?>"
														   class="label label-as-badge label-danger confirmation">
															<i class="fa fa-times"></i>
														</a>
													<?php endif; ?>
													<?php if($session->is_admin_logged_in()): ?>
														<a class="label label-as-badge label-danger"
														   href="admin_delete_comment.php?id=<?php echo urlencode($comment->id); ?>">
															<i class="fa fa-times"></i>
														</a>
													<?php endif; ?>
												<?php endif; ?>
												<br/>
												<?php $course = Course::find_by_id($comment->course_id); ?>
												<a href="forum?category=<?php echo urlencode($course->category_id); ?>&course=<?php echo urlencode($course->id); ?>">
													<?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?>
												</a>
											</div>
										</section>
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
					<h1><i class="fa fa-frown-o fa-5x"></i></h1>
				</div>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="member-forum-search" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="arial glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="30" maxlength="50" data-toggle="tooltip"
					       data-placement="top" title="جستجو کنید و اینتر بزنید" placeholder="جستجوی موضوع در انجمن"/>
				</div>
			</form>
			<h2>انجمن ها</h2>
			<?php echo courses($current_category, $current_course, TRUE); ?>
		</aside>
	</section>

<?php include_layout_template('footer.php'); ?>