<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
include_layout_template('admin_header.php');
include_layout_template('author_nav.php');
echo output_message($message);
?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<article>
		<h2>اطلاعات نویسنده <?php echo ucwords(strtolower($author->full_name())); ?></h2>
		<dl class="dl-horizontal">
			<dt>اسم کاربری:</dt>
			<dd><?php echo htmlentities($author->username); ?></dd>
			<dt>پسورد:</dt>
			<dd>&#x25cf;&#x25cf;&#x25cf;&#x25cf;&#x25cf;&#x25cf;</dd>
			<dt>نام:</dt>
			<dd><?php echo ! empty($author->first_name) ? htmlentities(ucfirst($author->first_name)) : '-'; ?></dd>
			<dt>نام خانوادگی:</dt>
			<dd><?php echo ! empty($author->last_name) ? htmlentities(ucfirst($author->last_name)) : '-'; ?></dd>
			<dt>ایمیل:</dt>
			<dd><?php echo ! empty($author->email) ? htmlentities(strtolower($author->email)) : '-'; ?></dd>
			<dt>ایمیل اختصاصی</dt>
			<dd><?php echo ! empty($author->parsclickmail) ? htmlentities(strtolower($author->parsclickmail)) : "-"; ?></dd>
			<dt>وضعیت:</dt>
			<dd><?php echo htmlentities($author->status == 1) ? 'فعال' : 'معوق'; ?></dd>
			<dt>&nbsp;</dt>
			<dd><a href="author_edit_profile.php" class="btn btn-success"> ویرایش</a></dd>
		</dl>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside class="members_menu">
		<h2>آواتار</h2>
		<?php if (empty($author->photo)): ?>
			<div>
				<span class="glyphicon glyphicon-user center" style="font-size: 150px;"></span>
			</div>
		<?php else: ?>
			<img class="img-circle" height="200" width="200" alt="Profile Picture"
			     src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>">
		<?php endif; ?>
	</aside>
</section>
<?php include_layout_template('admin_footer.php'); ?>
