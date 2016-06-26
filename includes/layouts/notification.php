<?php
global $session;
$session->confirm_logged_in();
$member        = Member::find_by_id($session->id);
$notifications = Notification::find_all();
?>
<div class="modal fade" id="notifyModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">تابلوی اعلانات</h4>
			</div>
			<div class="modal-body">
				<?php if (empty($notifications)): ?>
					<h4 class="text-muted">فعلا اعلانی نیست!</h4>
				<?php else: ?>
					<?php foreach ($notifications as $notification): ?>
						<?php $admin = Admin::find_by_id($notification->admin_id); ?>
						<section class="media">
							<img class="img-circle pull-right" width="60"
							     src="//www.gravatar.com/avatar/<?php echo md5($admin->email); ?>?s=100&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>"
							     alt="عکس ادمین">
							<h5 class="text-muted">
								<span>
									<?php echo $admin->full_name(); ?>
								</span>
								<span class="pull-left">
									<?php echo datetime_to_shamsi($notification->created, '*%d *%B، %Y'); ?>
								</span>
							</h5>
							<div class="media-body">
								<p><?php echo nl2br(strip_tags($notification->content, ARTICLE_ALLOWABLE_TAGS)); ?></p>
								<?php if ($notification->button_text && $notification->button_url): ?>
									<a class="btn btn-small btn-info pull-left" href="<?php echo $notification->button_url; ?>"
									   target="_blank" title="<?php echo $notification->button_text; ?>">
										<?php echo $notification->button_text; ?>
									</a>
								<?php endif; ?>
								<div class="clearfix"></div>
							</div>
							<?php echo (end($notifications) != $notification) ? '<hr>' : ''; ?>
						</section>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<!-- <div class="modal-footer"></div> -->
		</div>
	</div>
</div>