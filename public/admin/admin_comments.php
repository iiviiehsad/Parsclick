<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
find_selected_course();
if(!$current_course) {
	$session->message("هیچ درسی پیدا نشد!");
	redirect_to("admin_courses.php");
}
// Pagination
$page        = !empty($_GET["page"]) ? (int)$_GET["page"] : 1;
$per_page    = 5;
$total_count = Comment::count_comments_for_course($current_course->id);
$pagination  = new pagination($page, $per_page, $total_count);
$comments    = Comment::find_comments($current_course->id, $per_page, $pagination->offset());
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-6 col-lg-6">
		<article>
			<h3><i class="fa fa-comments fa-lg"></i> نظرات</h3>
			<?php foreach($comments as $comment) { ?>
				<section class="media">
					<?php $_member = Member::find_by_id($comment->member_id); ?>
					<img class="img-rounded pull-right" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50" alt="<?php echo $_member->email; ?>">
					<div class="media-body arial">
						<span class="badge">
							<span class="yekan"><?php echo htmlentities($_member->full_name()); ?></span>
							<?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
						<a class="badge label-danger" href="admin_delete_comment.php?id=<?php echo urlencode($comment->id); ?>">
							<i class="fa fa-times"></i>
						</a>
						<p><?php echo strip_tags($comment->body, '<strong><em><p><pre>'); ?></p>
					</div>
				</section>
			<?php } // end foreach comments ?>
			<?php if($pagination->total_page() > 1) { ?>
				<nav class="clearfix center">
					<ul class="pagination">
						<?php if($pagination->has_previous_page()) { ?>
							<li>
								<a href="admin_comments.php?course=<?php echo urlencode($current_course->id) ?>&page=<?php echo urlencode($pagination->previous_page()); ?>" aria-label="Previous">
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
									<a href="admin_comments.php?course=<?php echo urlencode($current_course->id); ?>&page=<?php echo urlencode($i); ?>"><?php echo $i; ?></a>
								</li>
							<?php } ?>
						<?php } ?>
						<?php if($pagination->has_next_page()) { ?>
							<li>
								<a href="admin_comments.php?course=<?php echo urlencode($current_course->id) ?>&page=<?php echo urlencode($pagination->next_page()); ?>" aria-label="Next">
									<span aria-hidden="true">بعدی</span>
								</a>
							</li>
						<?php } // end: if($pagination->has_next_page()) ?>
					</ul>
				</nav>
			<?php } // end pagination ?>
			<?php if(empty($comments)) { ?>
				<h3><span class="label label-default">نظری نیست</span></h3>
			<?php } ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-6 col-lg-6">
		<aside>
			<h2><i class="fa fa-film"></i> جزئیات درس</h2>
			<dl class="well dl-horizontal">
				<dt>اسم درس:</dt>
				<dd><?php echo htmlentities($current_course->name); ?></dd>
				<dt>توضیحات:</dt>
				<dd><small><?php echo htmlentities($current_course->content); ?></small></dd>
				<dt>موضوع:</dt>
				<dd><?php echo htmlentities(Category::find_by_id($current_course->category_id)->name); ?></dd>
				<dt>نویسنده:</dt>
				<dd><?php echo htmlentities(Author::find_by_id($current_course->author_id)->full_name()); ?></dd>
			</dl>
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				نظرات بی درنگ قابل حذف شدن هستند.
			</div>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>