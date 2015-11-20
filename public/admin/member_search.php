<?php require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
if(isset($_GET["q"]) && !empty($_GET["q"]) && $_GET["q"] != " ") {
	$member_set = Member::search($_GET["q"]);
} else { // this is a $_GET request
	$session->message("چیزی جستجو نکردید.");
	redirect_to("member_list.php");
}
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message);
?>
	<section class="main">
		<article>
			<?php if(!empty($member_set)) { ?>
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
							<th>تلفن</th>
							<th>ایمیل</th>
							<th colspan="2">عملیات</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($member_set as $member): ?>
							<tr>
								<td class="arial"><?php echo htmlentities($member->username); ?></td>
								<td><?php echo htmlentities(ucfirst(strtolower($member->first_name))); ?></td>
								<td><?php echo htmlentities(ucfirst(strtolower($member->last_name))); ?></td>
								<td><?php echo htmlentities($member->gender); ?></td>
								<td><?php echo htmlentities(ucwords(strtolower($member->address))); ?> <br/>
									<?php echo htmlentities(ucwords(strtolower($member->city))); ?> <br/>
									<?php echo htmlentities(strtoupper($member->post_code)); ?></td>
								<td><?php echo htmlentities($member->phone); ?></td>
								<td class="arial"><?php echo htmlentities(strtolower($member->email)); ?></td>
								<td>
									<a class="btn btn-small btn-primary arial" href="edit_member.php?id=<?php echo urlencode($member->id); ?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
									<a class="btn btn-small btn-danger arial" href="delete_member.php?id=<?php echo urlencode($member->id); ?>" onclick="return confirm('آیا مطمئن به حذف این عضو هستید؟');" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php } else {
				$session->message("چیزی پیدا نشد! دوباره سعی کنید.");
				redirect_to("member_list.php");
			} ?>
		</article>
	</section>
<?php include_layout_template("admin_footer.php"); ?>