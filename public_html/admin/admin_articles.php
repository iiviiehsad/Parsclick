<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
find_selected_article();
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_subject && $current_article): ?>
				<?php include_layout_template('article-info.php'); ?>
			<?php elseif($current_subject): ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2><i class="fa fa-list-alt"></i> تنظیم موضوع </h2>
					</div>
					<div class="panel-body">
						<dl class="dl-horizontal">
							<dt>اسم موضوع:</dt>
							<dd><?php echo htmlentities(ucwords($current_subject->name)); ?></dd>
							<dt>محل:</dt>
							<dd><?php echo $current_subject->position; ?></dd>
							<dt>نمایان:</dt>
							<dd><?php echo $current_subject->visible == 1 ? 'بله' : 'خیر'; ?></dd>
							<dt>&nbsp;</dt>
							<dd>
								<a class="btn btn-primary btn-small" href="edit_subject.php?subject=<?php echo urlencode($current_subject->id); ?>">
									<span class="glyphicon glyphicon-pencil"></span>
								</a>
							</dd>
						</dl>
						<?php if(Article::num_articles_for_subject($current_subject->id, FALSE)): ?>
						<hr>
						<?php include_layout_template('article-under-subject.php'); ?>
					</div>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<h2>لطفا یک مقاله یا یک موضوع انتخاب کنید.</h2>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<a class="arial btn btn-success pull-left" href="new_subject.php" title="Add New Subject"><span class="glyphicon glyphicon-plus"></span></a>
			<h2>موضوعات و مقالات</h2>
			<?php echo articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>