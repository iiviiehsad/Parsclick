<?php require_once("../../includes/initialize.php"); ?>
<?php $session->confirm_admin_logged_in(); ?>
<?php $filename = basename(__FILE__); ?>
<?php
if(isset($_GET["q"]) && !empty($_GET["q"]) && $_GET["q"] != " ") {
	$admin_set = Admin::search($_GET["q"]);
} else {
	$session->message("شما چیزی جستجو نکردید!");
	redirect_to("admin_list.php");
}
?>
<?php include_layout_template("admin_header.php"); ?>
<?php include("../_/components/php/admin_nav.php"); ?>

<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if(!empty($admin_set)) { ?>
				<h2><i class="fa fa-search"></i> نتیجه جستجو مدیران</h2>
				<div class="table-responsive">
					<table class="table">
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
								<td><?php echo htmlentities($admin->username); ?></td>
								<td><?php echo htmlentities(ucfirst(strtolower($admin->first_name))); ?></td>
								<td><?php echo htmlentities(ucfirst(strtolower($admin->last_name))); ?></td>
								<td><?php echo htmlentities(strtolower($admin->email)); ?></td>
								<td>
									<a class="btn btn-small btn-primary arial" href="edit_admin.php?id=<?php echo urlencode($admin->id); ?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
									<a class="btn btn-small btn-danger arial" href="delete_admin.php?id=<?php echo urlencode($admin->id); ?>" onclick="return confirm('آیا مطمئن هستید که می خواهید این مدیر را حذف کنید؟');" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php } else {
				$session->message("چیزی پیدا نشد! دوباره سعی کنید.");
				redirect_to("admin_list.php");
			} ?></article>
	</section>
	<section class=" sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="admin_search.php" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="search" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی مدیر ارشد"/>
				</div>
			</form>
			<h2>
				<a class="btn btn-success" href="new_admin.php"><i class="fa fa-plus"></i> مدیر ارشد اضافه کن</a>
			</h2>
			<p>برای اضافه کردن مدیر ارشد لطفا روی دگمه <i class="fa fa-plus"></i> کلیک کنید و اطلاعات را پر کنید.
			   لطفا تا آنجاییکه ممکن هست سعی کنید تمام جزئیات را پر کنید.</p>
		</aside>
	</section>

<?php include_layout_template("admin_footer.php"); ?>