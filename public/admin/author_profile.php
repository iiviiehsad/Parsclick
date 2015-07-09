<?php require_once("../../includes/initialize.php"); ?>
<?php $filename = basename(__FILE__); ?>
<?php $session->confirm_author_logged_in(); ?>
<?php $author = Author::find_by_id($session->id); ?>
<?php $author->check_status(); ?>
<?php $author = Author::find_by_id($session->id); ?>
<?php $author->check_status(); ?>
<?php include_layout_template("admin_header.php"); ?>
<?php include("../_/components/php/author_nav.php"); ?>
<?php echo output_message($message); ?>
<section class="main col-sm-12 col-md-9 col-lg-9">
	<article>
		<h2><i class="fa fa-user"></i> پروفایل <?php echo ucwords(strtolower($author->full_name())); ?></h2>
		<dl class="dl-horizontal">
			<dt>اسم کاربری:</dt>
			<dd class="arial"><?php echo htmlentities($author->username); ?></dd>
			<dt>پسورد:</dt>
			<dd>&#x25cf;&#x25cf;&#x25cf;&#x25cf;&#x25cf;&#x25cf;</dd>
			<dt>نام:</dt>
			<dd><?php echo !empty($author->first_name) ? htmlentities(ucfirst($author->first_name)) : "-"; ?></dd>
			<dt>نام خانوادگی:</dt>
			<dd><?php echo !empty($author->last_name) ? htmlentities(ucfirst($author->last_name)) : "-"; ?></dd>
			<dt>ایمیل:</dt>
			<dd class="arial"><?php echo !empty($author->email) ? htmlentities(strtolower($author->email)) : "-"; ?></dd>
			<dt>وضعیت:</dt>
			<dd><?php echo htmlentities($author->status == 1) ?  "فعال" : "معوق"; ?></dd>
			<dt>&nbsp;</dt>
			<dd><a href="author_edit_profile.php?id=<?php echo urlencode($author->id); ?>" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> ویرایش</a></dd>
		</dl>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside class="members_menu">
		<h2><i class="fa fa-picture-o"></i> عکس پروفایل</h2>
		<?php if(empty($author->photo)) { ?>
			<div class="well">
				<span class="glyphicon glyphicon-user center" style="font-size: 150px;"></span>
				<a class="btn btn-success center" href="author_edit_profile.php">
					<i class="fa fa-upload"></i> آپلود عکس
				</a>
			</div>
		<?php } else { ?>
			<img class="img-responsive img-thumbnail" height="200" width="200" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>">
		<?php } ?>
	</aside>
</section>
<?php include_layout_template("admin_footer.php"); ?>
