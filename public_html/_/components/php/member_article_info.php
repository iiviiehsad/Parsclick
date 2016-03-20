<h2>به قسمت مقالات خوش آمدید.</h2>
<section>
	<p class="lead">تعداد نویسندگان:
		<span class="label label-as-badge"><?php echo convert(count(Author::find_active_authors())); ?></span></p>
	<br/>
	<?php $authors = Author::find_active_authors(); ?>
	<?php foreach($authors as $author) : ?>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<div class="center">
				<?php if( ! empty($author->photo)) { ?>
					<img class="img-circle" style="width:120px;height:120px;" alt="<?php echo $author->full_name(); ?>" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
				<?php } else { ?>
					<img class="img-circle" width="120" alt="No Profile Picture" src="images/misc/default-gravatar-pic.png"/>
				<?php } ?>
			</div>
			<div class="center">
				<span class="label label-as-badge label-danger"><?php echo $author->full_name(); ?></span><br/>
				<p class="label label-as-badge label-info">
					<?php echo convert(count(Article::find_articles_for_author($author->id, TRUE))); ?>
					مقاله منتشر شده
				</p>
			</div>
		</div>
	<?php endforeach; ?>
</section>