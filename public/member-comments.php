<?php
require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$errors = "";
$body   = "";
if(!$current_course) {
	$session->message("شناسه درسی پیدا نشد!");
	redirect_to("member-courses");
}
if(isset($_POST["submit"])) {
	$member_id   = (int)$member->id;
	$body        = trim($_POST["body"]);
	$new_comment = Comment::make($member_id, $current_course->id, $body);
	if($new_comment && $new_comment->create()) {
		$session->message("نظر شما با موفقیت فرستاده شد.");
		redirect_to($_SERVER['HTTP_REFERER']);
	} else {
		$errors = "خطا در فرستادن نظر!";
	}
} else {
}
// Pagination
$page        = !empty($_GET["page"]) ? (int)$_GET["page"] : 1;
$per_page    = 5;
$total_count = Comment::count_comments_for_course($current_course->id);
$pagination  = new pagination($page, $per_page, $total_count);
$comments    = Comment::find_comments($current_course->id, $per_page, $pagination->offset());
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h3><i class="fa fa-comments fa-lg"></i> نظرات بر
				<a href="member-courses?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>"
				   data-toggle="tooltip" title="برگردید به درس"><?php echo htmlentities($current_course->name); ?></a>
			</h3>
			<?php foreach($comments as $comment) { ?>
				<section class="media">
					<?php $_member = Member::find_by_id($comment->member_id); ?>
					<img class="img-circle pull-right" width="50" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo DOMAIN . DS . 'images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->email; ?>">
					<div class="media-body">
						<span class="badge"><?php echo htmlentities($_member->first_name); ?></span>
						<span class="badge arial"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
						<?php if($comment->member_id === $session->id) { ?>
							<a href="member-delete-comment?id=<?php echo urlencode($comment->id); ?>" class="badge label-danger" onclick="return confirm('آیا مطمئن هستید؟')">
								<i class="fa fa-times"></i>
							</a>
						<?php } ?>
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
								<a href="member-comments?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>&page=<?php echo urlencode($pagination->previous_page()); ?>" aria-label="Previous">
									<span aria-hidden="true"> &lt;&lt; </span>
								</a>
							</li>
						<?php } // end: if($pagination->has_previous_page()) ?>
						<?php for($i = 1; $i < $pagination->total_page() + 1; $i++) { ?>
							<?php if($i == $page) { ?>
								<li class="active">
									<span><?php echo $i; ?></span>
								</li>
							<?php } else { ?>
								<li>
									<a href="member-comments?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>&page=<?php echo urlencode($i); ?>"><?php echo $i; ?></a>
								</li>
							<?php } ?>
						<?php } ?>
						<?php if($pagination->has_next_page()) { ?>
							<li>
								<a href="member-comments?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id) ?>&page=<?php echo urlencode($pagination->next_page()); ?>" aria-label="Next">
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
			<br/>
			<fieldset>
				<legend><i class="fa fa-comments-o"></i> فرم نظر</legend>
				<form class="form-horizontal" action="member-comments?course=<?php echo urlencode($current_course->id); ?>" method="post" role="form">
					<!--content-->
					<section class="row">
						<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
							<img class="img-circle pull-left" width="100" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($member->email); ?>?s=100&d=<?php echo DOMAIN . DS . 'images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $member->email; ?>">
						</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" rows="7" required placeholder=" نظرتان را اینجا وارد کنید و این تگ ها هم قابل استفاده اند <strong><pre><code><em><p>"></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
							<a class="btn btn-danger" href="member-courses?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								بفرست
							</button>
						</div>
					</section>
				</form>
			</fieldset>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
			<h2>درس ها و نظرات</h2>
			<?php echo member_comments_for_course($current_category, $current_course); ?>
		</aside>
	</section>
<?php include_layout_template("footer.php"); ?>