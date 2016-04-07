<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$filename  = basename(__FILE__);
$admin_set = Admin::find_all();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-users"></i> لیست مدیران
			                                ارشد<span class="badge arial"><?php echo convert(count($admin_set)); ?></span>
			</h2>
			<br/>
			<div class="table-responsive">
				<table class="table table-hover table-responsive table-condensed">
					<thead>
						<tr>
							<th>اسم کاربری</th>
							<th>نام</th>
							<th>نام خانوادگی</th>
							<th>ایمیل</th>
							<th colspan="2">عملیات</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($admin_set as $admin): ?>
							<tr>
								<td class="arial">
									<img class="img-circle" src="http://gravatar.com/avatar/<?php echo md5($admin->email); ?>?s=30" alt="<?php echo $admin->email; ?>">
									<?php echo htmlentities($admin->username); ?>
								</td>
								<td><?php echo htmlentities(ucfirst(strtolower($admin->first_name))); ?></td>
								<td><?php echo htmlentities(ucfirst(strtolower($admin->last_name))); ?></td>
								<td class="arial">
									<small><?php echo htmlentities(strtolower($admin->email)); ?></small>
								</td>
								<td>
									<a class="btn btn-small btn-primary arial" href="edit_admin.php?id=<?php echo urlencode($admin->id); ?>" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
									<?php if($admin->id != $session->id): ?>
										<a class="btn btn-small btn-danger arial" href="delete_admin.php?id=<?php echo urlencode($admin->id); ?>" onclick="return confirm('آیا مطمئن هستید که می خواهید این مدیر را حذف کنید؟');" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="admin_search.php" method="get">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی مدیر ارشد"/>
				</div>
			</form>
			<h2>
				<a class="btn btn-success" href="new_admin.php"><i class="fa fa-plus"></i> مدیر ارشد اضافه کن</a>
			</h2>
			<p>برای اضافه کردن مدیر ارشد لطفا روی دگمه <i class="fa fa-plus"></i> کلیک کنید و اطلاعات را پر کنید.
			   لطفا تا آنجاییکه ممکن هست سعی کنید تمام جزئیات را پر کنید.</p>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>