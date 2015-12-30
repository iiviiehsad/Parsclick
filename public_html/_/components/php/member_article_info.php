<h2>به قسمت مقالات خوش آمدید.</h2><br>
<section>
	<h4>تعداد نویسندگان: <span class="badge arial"><?php echo count(Author::find_active_authors()); ?></span></h4>
	<?php $authors = Author::find_active_authors(); ?>
	<?php foreach($authors as $author) { ?>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<div class="thumbnail">
				<?php if( ! empty($author->photo)) { ?>
					<img class="img-circle" width="70" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
				<?php } else { ?>
					<img class="img-circle" width="70" alt="No Profile Picture" src="images/misc/default-gravatar-pic.png"/>
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
</section>