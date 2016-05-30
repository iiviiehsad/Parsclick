<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$author_set = Author::find_all();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-users"></i> لیست نویسندگان<span
						class="badge"><?php echo convert(count($author_set)); ?></span>
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
						<?php foreach ($author_set as $author): ?>
							<tr>
								<td class="arial">
									<?php if ( ! empty($author->photo)): ?>
										<img class="img-circle pull-right" width="30" alt="Profile Picture"
										     src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
									<?php else: ?>
										<img class="img-circle pull-right" width="30" alt="Profile Picture"
										     src="../images/misc/default-gravatar-pic.png"/>
									<?php endif; ?>
									&nbsp;
									<?php echo htmlentities($author->username); ?>
								</td>
								<td><?php echo htmlentities(ucfirst(strtolower($author->first_name))); ?></td>
								<td><?php echo htmlentities(ucfirst(strtolower($author->last_name))); ?></td>
								<td class="arial">
									<small><?php echo htmlentities(strtolower($author->email)); ?></small>
								</td>
								<td>
									<a class="btn btn-small btn-primary arial"
									   href="edit_author.php?id=<?php echo urlencode($author->id); ?>" title="Edit"><span
												class="glyphicon glyphicon-pencil"></span></a>
									<a class="btn btn-small btn-danger arial confirmation"
									   href="delete_author.php?id=<?php echo urlencode($author->id); ?>" title="Delete"><span
												class="glyphicon glyphicon-trash"></span></a>
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