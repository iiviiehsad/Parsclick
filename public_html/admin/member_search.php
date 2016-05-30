<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$search_query = trim($_GET['q']);
if (isset($search_query) && ! empty($search_query)) {
	$member_set = Member::search($search_query);
} else { // this is a $_GET request
	$session->message('چیزی جستجو نکردید.');
	redirect_to('member_list.php');
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main">
		<article>
			<?php if ( ! empty($member_set)): ?>
				<h2><i class="fa fa-search"></i> نتیجه جستجوی اعضا </h2>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>اسم کاربری</th>
								<th>نام</th>
								<th>نام خانوادگی</th>
								<th>جنس</th>
								<th>آدرس</th>
								<th>ایمیل</th>
								<th colspan="2">عملیات</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($member_set as $member): ?>
								<tr>
									<td class="arial"><?php echo htmlentities($member->username); ?></td>
									<td><?php echo htmlentities(ucfirst(strtolower($member->first_name))); ?></td>
									<td><?php echo htmlentities(ucfirst(strtolower($member->last_name))); ?></td>
									<td><?php echo htmlentities($member->gender); ?></td>
									<td>
										<?php echo htmlentities(ucwords(strtolower($member->address))); ?> <br/>
										<?php echo htmlentities(ucwords(strtolower($member->city))); ?> <br/>
									</td>
									<td class="arial"><?php echo htmlentities(strtolower($member->email)); ?></td>
									<td>
										<a class="btn btn-small btn-primary arial"
										   href="edit_member.php?id=<?php echo urlencode($member->id); ?>" title="Edit"><span
													class="glyphicon glyphicon-edit"></span></a>
										<a class="btn btn-small btn-danger arial confirmation"
										   href="delete_member.php?id=<?php echo urlencode($member->id); ?>" title="Delete"><span
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
<?php include_layout_template('admin_footer.php'); ?>