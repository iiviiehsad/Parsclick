<?php require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$errors    = "";
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
		redirect_to("member-comments?course={$current_course->id}");
	} else {
		$errors = "خطا در فرستادن نظر!";
	}
} else {
	$body = "";
}
// Pagination
$page        = !empty($_GET["page"]) ? (int)$_GET["page"] : 1;
$per_page    = 5;
$total_count = Comment::count_comments_for_course($current_course->id);
$pagination  = new pagination($page, $per_page, $total_count);
$sql         = "SELECT * FROM comments WHERE course_id = {$current_course->id} LIMIT {$per_page} OFFSET {$pagination->offset()}";
$comments    = Comment::find_by_sql($sql);
?>

<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h3><i class="fa fa-comments fa-lg"></i> نظرات بر <?php echo htmlentities($current_course->name); ?></h3>
			<?php foreach($comments as $comment) { ?>
				<section class="media">
					<?php $_member = Member::find_by_id($comment->member_id); ?>
					<?php if(empty($_member->photo)) { ?>
						<i style="font-size:50px;" class="fa fa-user pull-right"></i>
					<?php } else { ?>
						<img style="width:50px;height:50px;" class="img-responsive img-rounded pull-right" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($_member->photo); ?>"/>
					<?php } ?>
					<div class="media-body">
						<span class="badge"><?php echo htmlentities($_member->first_name); ?></span>
						<span class="badge arial"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
						<?php if($comment->member_id === $session->id) { ?>
							<a href="member-delete-comment?id=<?php echo urldecode($comment->id); ?>" class="badge">
								<i class="fa fa-trash-o"></i>
							</a>
						<?php } ?>
						<p style="margin-top:4px;"><?php echo strip_tags($comment->body, '<strong><em><p>'); ?></p>
					</div>
				</section>
			<?php } // end foreach comments?>
			<?php if($pagination->total_page() > 1) { ?>
				<nav class="clearfix center">
					<ul class="pagination">
						<?php if($pagination->has_previous_page()) { ?>
							<li>
								<a href="member-comments?course=<?php echo urldecode($current_course->id) ?>&page=<?php echo urldecode($pagination->previous_page()); ?>" aria-label="Previous">
									<span aria-hidden="true">قبلی</span>
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
									<a href="member-comments?course=<?php echo urldecode($current_course->id); ?>&page=<?php echo urldecode($i); ?>"><?php echo $i; ?></a>
								</li>
							<?php } ?>
						<?php } ?>
						<?php if($pagination->has_next_page()) { ?>
							<li>
								<a href="member-comments?course=<?php echo urldecode($current_course->id) ?>&page=<?php echo urldecode($pagination->next_page()); ?>" aria-label="Next">
									<span aria-hidden="true">بعدی</span>
								</a>
							</li>
						<?php } // end: if($pagination->has_next_page()) ?>
					</ul>
				</nav>
			<?php } // end pagination?>
			<?php if(empty($comments)) { ?>
				<h3><span class="badge">نظری وجود ندارد</span></h3>
			<?php } ?>
			<br/>
			<fieldset>
				<legend><i class="fa fa-comments-o"></i> فرم نظر</legend>
				<form class="form-horizontal" action="member-comments?course=<?php echo urlencode($current_course->id); ?>" method="post" role="form">
					<!--content-->
					<section class="row">
						<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
							<?php if(empty($member->photo)) { ?>
								<i style="font-size:50px;margin-right:25px;" class="fa fa-user pull-right"></i>
							<?php } else { ?>
								<img style="width:50px;height:50px;margin-right:25px;" class="img-responsive img-rounded pull-right" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($member->photo); ?>">
							<?php } ?>
						</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" rows="7" required placeholder=" نظرتان را اینجا وارد کنید و این تگ ها هم قابل استفاده اند <strong><em><p>"></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
							<a class="btn btn-danger" href="member-courses">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								فرستادن
							</button>
						</div>
					</section>
				</form>
			</fieldset>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
			<h2>موضوعات</h2>
			<?php echo member_courses($current_category, $current_course); ?>
		</aside>
	</section>

<?php include_layout_template("footer.php"); ?>