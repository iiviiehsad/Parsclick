<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = 'پارس کلیک - انجمن';
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$errors = "";
$body   = "";
if( ! $current_course || ! $current_category) {
	$current_course = $current_category = Course::find_newest_course();
} else {
}
// Pagination
$page        = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
$per_page    = 20;
$total_count = Comment::count_comments_for_course($current_course->id);
$pagination  = new pagination($page, $per_page, $total_count);
$comments    = Comment::find_comments($current_course->id, $per_page, $pagination->offset());
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h3><i class="fa fa-comments fa-lg"></i> انجمن
				<a href="member-courses?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>"
				   data-toggle="tooltip" title="برگردید به درس"><?php echo htmlentities($current_course->name); ?></a>
			</h3>
			<?php if(empty($comments)): ?>
				<h3><span class="badge">سوال یا نظری وجود ندارد. اولین نفری باشید که نظر می دهید.</span></h3>
			<?php endif; ?>
			<fieldset>
				<legend></legend>
				<form class="form-horizontal submit-comment" action="add-comment.php" method="POST" role="form">
					<!--content-->
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
						<img class="img-circle pull-right hidden-sm" width="100" src="//www.gravatar.com/avatar/<?php echo md5($member->email); ?>?s=100&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->username; ?>">
					</label>
					<input type="hidden" name="course" value="<?php echo urlencode($current_course->id); ?>">
					<div class="controls">
							<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" rows="3" required
							          placeholder="سوال یا نظرتان را اینجا وارد کنید و کد ها را داخل تگ <pre> وارد کنید"></textarea>
					</div>
					<!--buttons-->
					<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
						<button class="btn btn-primary" name="submit" id="submit" type="submit">
							بفرست
						</button>
					</div>
				</form>
			</fieldset>
			<hr/>
			<div id="ajax-comments">
				<?php foreach($comments as $comment): ?>
					<section class="media">
						<?php $_member = Member::find_by_id($comment->member_id); ?>
						<img class="img-circle pull-right" width="50" style="padding-right:0;" src="//www.gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->username; ?>">
						<div class="media-body">
							<span class="label label-as-badge label-success"><?php echo htmlentities($_member->first_name); ?></span>
							<span class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_shamsi($comment->created)); ?></span>
							<?php if($comment->member_id === $session->id): ?>
								<a href="member-delete-comment?id=<?php echo urlencode($comment->id); ?>" class="label label-as-badge label-danger" onclick="return confirm('آیا مطمئن هستید؟')">
									<i class="fa fa-times"></i>
								</a>
							<?php endif; ?>
							<br/>
							<?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?>
						</div>
					</section>
				<?php endforeach; ?>
				<?php echo paginate($pagination, $page, "member-comments", "category={$current_course->category_id}", "course={$current_course->id}"); ?>
			</div>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
			<h2>انجمن ها</h2>
			<?php echo member_comments_for_course($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template("footer.php"); ?>