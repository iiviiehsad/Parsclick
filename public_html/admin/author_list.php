<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$author_set = Author::find_all();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<div class="row">
		<section class="sidebar col-lg-4 pull-right">
			<aside>
				<h2><i class="fa fa-users"></i> لیست نویسندگان<span
							class="badge"><?php echo convert(count($author_set)); ?></span>
				</h2>
			</aside>
		</section>
		<section class="sidebar col-lg-4 pull-left">
			<aside>
				<form class="form-inline" action="author_search.php" method="get">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input type="text" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی نویسنده"/>
					</div>
				</form>
				<a class="btn btn-success" href="new_author.php"><i class="fa fa-plus"></i> نویسنده اضافه کن</a>
			</aside>
		</section>
	</div>
	<section class="main col-sm-12 col-md-12 col-lg-12">
		<article>
			<div class="table-responsive">
				<table class="table table-hover table-responsive table-condensed">
					<thead>
						<tr>
							<th>اسم کاربری</th>
							<th>نام</th>
							<th>نام خانوادگی</th>
							<th>ایمیل</th>
							<th colspan="3">عملیات</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($author_set as $author): ?>
							<tr class="<?php echo status($author); ?>">
								<td>
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
								<td><?php echo htmlentities($author->first_name); ?></td>
								<td><?php echo htmlentities($author->last_name); ?></td>
								<td>
									<small><?php echo htmlentities($author->email); ?></small>
								</td>
								<td>
									<a class="btn btn-small btn-primary"
									   href="edit_author.php?id=<?php echo urlencode($author->id); ?>" title="Edit">
										<span class="glyphicon glyphicon-pencil"></span>
									</a>
									<a class="btn btn-small btn-danger confirmation"
									   href="delete_author.php?id=<?php echo urlencode($author->id); ?>" title="Delete">
										<span class="glyphicon glyphicon-trash"></span>
									</a>
									<?php if (idle(find_newest_date([
											Article::find_newest_article_for_author($author->id) ?
													Article::find_newest_article_for_author($author->id)->created_at : time() >
													time_left($author->created_at, '+1 month'),
											Course::find_newest_course_for_author($author->id) ?
													Course::find_newest_course_for_author($author->id)->created_at : time() >
													time_left($author->created_at, '+1 month'),
									]))) : ?>
										<span class="btn btn-small btn-warning" disabled>
											<i class="fa fa-exclamation-triangle"></i>
										</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</article>
	</section>
<?php include_layout_template('admin_footer.php'); ?>