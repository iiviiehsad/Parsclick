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
						<h2><?php echo htmlentities(ucwords($current_subject->name)); ?></h2>
						<h5>محل:&nbsp;<?php echo convert($current_subject->position); ?></h5>
						<h5>نمایان:&nbsp;<?php echo $current_subject->visible == 1 ? 'بله' : 'خیر'; ?></h5>
						<a class="btn btn-primary" href="edit_subject.php?subject=<?php echo urlencode($current_subject->id); ?>">
							ویرایش
						</a>
					</div>
					<div class="panel-body">
						<?php if(Article::num_articles_for_subject($current_subject->id, FALSE)): ?>
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
			<a class="arial btn btn-success pull-left" href="new_subject.php" title="Add New Subject"><span
						class="glyphicon glyphicon-plus"></span></a>
			<h2>موضوعات و مقالات</h2>
			<?php echo articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>