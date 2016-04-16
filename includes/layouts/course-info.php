<?php global $session; global $current_course; global $json; ?>
<h3>
	<img class="img-rounded pull-left screenshot" width="30%"
	     src="<?php echo reset($json['items'])['snippet']['thumbnails']['maxres']['url']; ?>"
	     alt="<?php echo $current_course->name; ?>">
	<?php echo htmlentities(ucwords($current_course->name)); ?>
	<?php if($current_course->recent(end($json['items'])['snippet']['publishedAt']) && ! $current_course->recent()): ?>
		<kbd>بروز شد</kbd>
	<?php endif; ?>
	<?php if($current_course->recent()): ?>
		<kbd>تازه</kbd>
	<?php endif; ?>
</h3>
<h5>
	<i class="fa fa-user fa-lg"></i>&nbsp;
	<?php echo isset($current_course->author_id) ? htmlentities(Author::find_by_id($current_course->author_id)->full_name()) : ''; ?>
</h5>
<h5>
	<i class="fa fa-calendar"></i>&nbsp;
	<?php echo htmlentities(datetime_to_text($current_course->created_at)); ?>
</h5>
<h5>
	<i class="fa fa-calendar"></i>&nbsp;
	<?php echo datetime_to_shamsi($current_course->created_at); ?>
</h5>
<?php if($session->is_admin_logged_in() || $session->is_author_logged_in()): ?>
	<h5><i class="fa fa-eye fa-lg"></i>&nbsp;
		<?php echo $current_course->visible == 1 ? '<span class="text-success">بله</span>' : '<span class="text-danger">خیر</span>'; ?>
	</h5>
	<h5><i class="fa fa-list-ol fa-lg"></i>&nbsp;
		<?php echo convert($current_course->position); ?>
	</h5>
<?php endif ?>
<?php if( ! empty($current_course->content)): ?>
	<p><?php echo nl2br(strip_tags($current_course->content, '<strong><em><p><code><pre><mark><kbd><ul><ol><li><img><a>')); ?></p>
<?php endif; ?>