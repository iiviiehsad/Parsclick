<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message);
?>
<section class="main col-sm-12 col-md-12 col-lg-12">
	<article>
		<h4>تعداد نویسندگان: <span class="badge arial"><?php echo count(Author::find_active_authors()); ?></span></h4>
	<?php $authors = Author::find_active_authors(); ?>
	<?php foreach($authors as $author) { ?>
		<div class="col-sm-12 col-md-2 col-lg-2">
			<div class="thumbnail">
				<?php if( ! empty($author->photo)) { ?>
					<img width="100%" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
				<?php } else { ?>
					<img width="100%" alt="No Profile Picture" src="../images/misc/default-gravatar-pic.png"/>
				<?php } ?>
				&nbsp;
				<div class="caption center">
					<h4>
						<?php echo $author->full_name(); ?>
					</h4>
					<?php echo convert(count(Article::find_articles_for_author($author->id, TRUE))); ?>
					مقاله منتشر شده
				</div>
			</div>
		</div>
	<?php } ?>
		</article>
</section>
<?php include_layout_template("admin_footer.php"); ?>
