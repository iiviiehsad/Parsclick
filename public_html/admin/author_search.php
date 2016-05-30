<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$search_query = trim($_GET['q']);
if (isset($search_query) && ! empty($search_query)) {
	$author_set = Author::search($search_query);
} else { // this is a $_GET request
	$session->message('شما چیزی جستجو نکردید!');
	redirect_to('author_list.php');
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if ( ! empty($author_set)): ?>
				<h2><i class="fa fa-search"></i> نتیجه جستجو نویسندگان</h2>
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
							<?php foreach ($author_set as $author): ?>
								<tr>
									<td><?php echo htmlentities($author->username); ?></td>
									<td><?php echo htmlentities(ucfirst(strtolower($author->first_name))); ?></td>
									<td><?php echo htmlentities(ucfirst(strtolower($author->last_name))); ?></td>
									<td><?php echo htmlentities(strtolower($author->email)); ?></td>
									<td>
										<a class="btn btn-small btn-primary arial"
										   href="edit_author.php?id=<?php echo urlencode($author->id); ?>" title="Edit"><span
													class="glyphicon glyphicon-edit"></span></a>
										<a class="btn btn-small btn-danger arial confirmation"
										   href="delete_author.php?id=<?php echo urlencode($author->id); ?>" title="Delete"><span
													class="glyphicon glyphicon-trash"></span></a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="center">
					<h3>برای <?php echo $search_query; ?></h3>
					<h1>چیزی پیدا نشد!</h1>
					<h1><i class="fa fa-frown-o fa-5x"></i></h1>
				</div>
			<?php endif; ?>
		</article>
	</section>
	<section class=" sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="author_search.php" method="get">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی نویسنده"/>
				</div>
			</form>
			<h2>
				<a class="btn btn-success" href="new_author.php"><i class="fa fa-plus"></i> نویسنده اضافه کن</a>
			</h2>
			<p>برای اضافه کردن نویسنده لطفا روی دگمه <i class="fa fa-plus"></i> کلیک کنید و اطلاعات را پر کنید.
			   لطفا تا آنجاییکه ممکن هست سعی کنید تمام جزئیات را پر کنید.</p>
		</aside>
	</section>

<?php include_layout_template('admin_footer.php'); ?>