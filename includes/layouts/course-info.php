<?php global $current_course; global $session; ?>
<h1>
	<?php echo htmlentities(ucwords($current_course->name)); ?>
</h1>
<h4>
	<i class="fa fa-calendar"></i>&nbsp;
	<?php echo htmlentities(datetime_to_text($current_course->created_at)); ?>
</h4>
<h4>
	<i class="fa fa-calendar"></i>&nbsp;
	<?php echo datetime_to_shamsi($current_course->created_at); ?>
</h4>
<h4>
	<i class="fa fa-user fa-lg"></i>&nbsp;
	<?php echo isset($current_course->author_id) ? htmlentities(Author::find_by_id($current_course->author_id)->full_name()) : ''; ?>
</h4>
<?php if($session->is_admin_logged_in() || $session->is_author_logged_in()): ?>
	<h4><i class="fa fa-eye fa-lg"></i>&nbsp;
		<?php echo $current_course->visible == 1 ? '<span class="text-success">بله</span>' : '<span class="text-danger">خیر</span>'; ?>
	</h4>
	<h4><i class="fa fa-list-ol fa-lg"></i>&nbsp;
		<?php echo convert($current_course->position); ?>
	</h4>
<?php endif ?>
<?php if( ! empty($current_course->content)): ?>
	<p><?php echo nl2br(strip_tags($current_course->content, '<strong><em><p><code><pre><mark><kbd><ul><ol><li><img><a>')); ?></p>
<?php endif; ?>