<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$notifications = Notification::find_all();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
<section class="main col-sm-12 col-md-12 col-lg-12">
	<article>
		<h2>
			<i class="fa fa-bell"></i> اعلانات
			<a class="btn btn-success" href="new_notification.php"><i class="fa fa-plus"></i></a>
		</h2>
		<br/>
		<?php if (empty($notifications)): ?>
			<h3>
				<span class="label label-as-badge label-danger">اعلانی وجود ندارد</span>
			</h3>
		<?php else: ?>
			<div class="table-responsive">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>مدیر</th>
							<th width="60%">محتوا</th>
							<th>تاریخ</th>
							<th>عملیات</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($notifications as $notification): ?>
							<?php $admin = Admin::find_by_id($notification->admin_id); ?>
							<tr>
								<td class="center">
									<div>
										<img class="img-circle" src="//gravatar.com/avatar/<?php echo md5($admin->email); ?>?s=100"
										     alt="<?php echo $admin->email; ?>">
									</div>
									<div class="label label-as-badge label-warning">
										<?php echo $admin->username; ?>
									</div>
								</td>
								<td>
									<small><?php echo nl2br(strip_tags($notification->content, ARTICLE_ALLOWABLE_TAGS)); ?></small>
									<?php if ($notification->button_text && $notification->button_url): ?>
										<a class="btn btn-small btn-info pull-left" href="<?php echo $notification->button_url; ?>">
											<?php echo $notification->button_text; ?>
										</a>
									<?php endif; ?>
								</td>
								<td>
									<?php echo datetime_to_shamsi($notification->created, '*%d *%B، %Y'); ?>
								</td>
								<td>
									<?php if ($admin->id == $session->id): ?>
										<a class="btn btn-small btn-danger confirmation"
										   href="delete_notification.php?id=<?php echo urlencode($notification->id); ?>"
										   title="Delete"><span
													class="glyphicon glyphicon-trash"></span></a>
									<?php else: ?>
										<i title="قادر به حذف نیستید" class="fa fa-ban fa-3x text-danger"></i>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</article>
</section>
<?php include_layout_template('admin_footer.php'); ?>
