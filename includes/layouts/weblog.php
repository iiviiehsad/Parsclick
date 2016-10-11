<aside>
	<div class="list-group">
		<a class="list-group-item" href="http://blog.parsclick.net/" target="_blank">وبلاگ پارس کلیک <span
					class="label label-as-badge label-info pull-left">اخبار</span></a>
	</div>
	<div class="list-group">
		<?php
		$parsclick_rss = parse_rss_feed('http://parsclick.blogspot.com/feeds/posts/default?alt=rss&max-results=10');
		foreach ($parsclick_rss->channel->item as $item) : ?>
			<a target="_blank" class="list-group-item" href="<?php echo $item->link; ?>">
				<?php echo $item->title; ?>
			</a>
		<?php endforeach; ?>
	</div>
</aside>