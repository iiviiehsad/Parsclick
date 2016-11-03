<?php
$khabaronline_rss = parse_rss_feed('http://khabaronline.ir/RSS/Service/ict');
$digiato_rss      = parse_rss_feed('http://feeds.feedburner.com/Digiato');
$radiofarda_rss   = parse_rss_feed('http://www.radiofarda.com/api/z_oqmergq_');
$voa_rss          = parse_rss_feed('http://ir.voanews.com/api/zyupoeqpmi');
?>

<h2 class="bbcnassim">سرخط اخبار فناوری</h2>

<ul class="list-group">

	<?php foreach ($digiato_rss->channel->item as $item) : ?>
		<li class="list-group-item">
			<img src="<?php echo $item->image->url; ?>" width="25%" height="100%" class="img-rounded screenshot pull-left">
			<a href="javascript:popup('<?php echo $item->shortlink; ?>')" target="_blank"><?php echo $item->title; ?></a>
		</li>
	<?php endforeach; ?>

	<?php foreach ($radiofarda_rss->channel->item as $item) : ?>
		<li class="list-group-item">
			<img src="<?php echo $item->enclosure['url']; ?>" width="25%" height="100%"
			     class="img-rounded screenshot pull-left">
			<p title="<?php echo $item->description; ?>"
			   data-toggle="tooltip" data-placement="top" style="cursor:help;">
				<?php echo $item->title; ?>
			</p>
		</li>
	<?php endforeach; ?>

	<?php foreach ($voa_rss->channel->item as $item) : ?>
		<li class="list-group-item">
			<img src="<?php echo $item->enclosure['url']; ?>" width="25%" height="100%"
			     class="img-rounded screenshot pull-left">
			<p title="<?php echo $item->description; ?>"
			   data-toggle="tooltip" data-placement="top" style="cursor:help;">
				<?php echo $item->title; ?>
			</p>
		</li>
	<?php endforeach; ?>

	<?php foreach ($khabaronline_rss->channel->item as $item) : ?>
		<a href="javascript:popup('<?php echo $item->link; ?>')" target="_blank" class="list-group-item">
			<?php echo $item->title; ?>
		</a>
	<?php endforeach; ?>

</ul>
