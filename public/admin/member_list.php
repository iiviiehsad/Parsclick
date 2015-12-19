<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename   = basename(__FILE__);
$member_set = Member::find_all();
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message);
?>
	<section class="sidebar col col-lg-4 pull-right">
		<aside>
			<form class="form-inline" action="member_search.php" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="40" maxlength="50" placeholder="جستجوی اعضا"/>
				</div>
			</form>
		</aside>
	</section>
	<section class="sidebar col col-lg-4 pull-left">
		<aside>
			<a class="btn btn-success btn-block" href="new_member.php"><i class="fa fa-plus"></i> اضافه کردن عضو جدید</a>
		</aside>
	</section>
	<section class="main col col-lg-12">
		<article>
			<h2><i class="fa fa-users"></i> لیست اعضا <span class="badge arial"><?php echo count($member_set); ?></span></h2>
			<br/>
			<div class="table-responsive">
				<table class="table table-hover table-condensed">
					<thead>
					<tr>
						<th>آواتار</th>
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
					<?php foreach($member_set as $member): ?>
						<tr class="
					<?php if($member->status == 0) {
							echo "warning";
						} elseif($member->status == 1) {
							echo "success";
						} elseif($member->status == 2) {
							echo "danger";
						} else {
							echo "";
						} ?>">
							<td><img class="img-circle" src="//www.gravatar.com/avatar/<?php echo md5($member->email); ?>?s=30"></td>
							<td class="arial">
								<small><?php echo htmlentities($member->username); ?></small>
							</td>
							<td>
								<small><?php echo htmlentities(ucfirst(strtolower($member->first_name))); ?></small>
							</td>
							<td>
								<small><?php echo htmlentities(ucfirst(strtolower($member->last_name))); ?></small>
							</td>
							<td>
								<small><?php echo htmlentities($member->gender); ?></small>
							</td>
							<td>
								<small>
									<?php echo htmlentities(ucwords(strtolower($member->address))); ?> <br/>
									<?php echo htmlentities(ucwords(strtolower($member->city))); ?> <br/>
								</small>
							</td>
							<td class="arial">
								<small><?php echo htmlentities($member->email); ?></small>
							</td>
							<td>
								<a class="btn btn-small btn-primary arial" href="edit_member.php?id=<?php echo urlencode($member->id); ?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
								<a class="btn btn-small btn-danger arial" href="delete_member.php?id=<?php echo urlencode($member->id); ?>" onclick="return confirm('آیا مطمئن به حذف این عضو هستید؟');" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</article>
	</section>
<?php include_layout_template("admin_footer.php"); ?>