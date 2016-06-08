<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
include_layout_template('admin_header.php');
include_layout_template('author_nav.php');
echo output_message($message);
?>
<section class="main col-sm-12 col-md-12 col-lg-12">
	<article>
		<h1>ارتباط با همکاران</h1>
		<p class="lead">تعداد نویسندگان:
			<span class="label label-as-badge"><?php echo convert(count(Author::find_active_authors())); ?></span></p>
		<br/>
		<?php $authors = Author::find_active_authors(); ?>
		<?php foreach ($authors as $author): ?>
			<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
				<div class="center">
					<?php if ( ! empty($author->photo)): ?>
						<img class="img-circle" style="width:200px;height:200px;" alt="<?php echo $author->full_name(); ?>"
						     src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
					<?php else: ?>
						<img class="img-circle" width="200" alt="No Profile Picture" src="../images/misc/default-gravatar-pic.png"/>
					<?php endif; ?>
				</div>
				<div class="center">
					<span class="label label-as-badge label-danger"><?php echo $author->full_name(); ?></span>
					<br/>
					<?php if ( ! empty(Article::count_articles_for_author($author->id, TRUE))): ?>
						<span class="label label-as-badge label-info">
						<?php echo convert(Article::count_articles_for_author($author->id, TRUE)) ?>
							مقاله منتشر شده
						</span>
					<?php else: ?>
						<span class="label label-as-badge label-primary">
							مقاله ای منتشر نشده
						</span>
					<?php endif; ?>
					<?php if ( ! empty($author->parsclickmail)): ?>
						<br/>
						<p class="label label-as-badge label-warning">
							<a data-toggle="tooltip" target="_blank" title="<?php echo $author->parsclickmail; ?>"
							   href="mailto:<?php echo $author->parsclickmail; ?>">
								ایمیل پارس کلیکی
							</a>
						</p>
					<?php else: ?>
						<br/>
						<p class="label label-as-badge label-primary">ایمیل پارس کلیک موجود نیست</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</article>
</section>
<?php include_layout_template('admin_footer.php'); ?>
