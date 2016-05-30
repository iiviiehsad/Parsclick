<?php require_once('../includes/initialize.php');
$title    = 'پارس کلیک - انجمن';
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
$errors = '';
$body   = '';
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('member_nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article id="comments">
			<?php if( ! $current_course && ! $current_category): ?>
				<?php include_layout_template('member-forum-info.php'); ?>
			<?php else: ?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">
							<a class="bright" href="forum" data-toggle="tooltip" data-placement="bottom"
							   title="اطلاعات انجمن">انجمن</a>
							<a class="bright"
							   href="member-courses?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>"
							   data-toggle="tooltip" title="برگردید به درس"><?php echo htmlentities($current_course->name); ?></a>
							<span class="badge">
								<?php echo convert(count($current_course->comments())); ?> دیدگاه
							</span>
						</h3>
					</div>
					<div class="panel-body">
						<fieldset>
							<form class="form-horizontal submit-comment" action="add-comment.php" method="POST" role="form">
								<!--content-->
								<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" for="content">
									<img class="img-circle pull-right hidden-sm" width="100"
									     src="//www.gravatar.com/avatar/<?php echo md5($member->email); ?>?s=100&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>"
									     alt="<?php echo $member->username; ?>">
								</label>
								<input type="hidden" name="course" value="<?php echo urlencode($current_course->id); ?>">
								<div class="controls">
									<textarea class="col-xs-12 col-sm-10 col-md-10 col-lg-10" name="body" id="body" rows="3" required
									          placeholder="سوال یا نظرتان را اینجا وارد کنید"></textarea>
								</div>
								<!--buttons-->
								<div class="controls col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
									<button class="btn btn-primary" name="submit" id="submit" type="submit">
										بفرست
									</button>
								</div>
							</form>
						</fieldset>
					</div>
				</div>
				<div id="forum">
					<div id="ajax-comments">
						<?php include_layout_template('course-comments.php'); ?>
					</div>
				</div>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="members_menu">
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