<?php
$radiofarda_rss = parse_rss_feed('http://www.radiofarda.com/api/z_oqmergq_');
$voa_rss        = parse_rss_feed('http://ir.voanews.com/api/zyupoeqpmi');
?>
<h2 class="bbcnassim">سرخط اخبار فناوری</h2>
<?php foreach ($radiofarda_rss->channel->item as $item) : ?>
	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" title="<?php echo $item->description; ?>"
	     data-toggle="tooltip" data-placement="top" style="cursor:help;">
		<?php echo $item->title; ?>
	</div>
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		<img src="<?php echo $item->enclosure['url']; ?>" width="100%" class=" img-rounded screenshot">
	</div>
<?php endforeach; ?>

<?php foreach ($voa_rss->channel->item as $item) : ?>
	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" title="<?php echo $item->description; ?>"
	     data-toggle="tooltip" data-placement="top" style="cursor:help;">
		<?php echo $item->title; ?>
	</div>
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		<img src="<?php echo $item->enclosure['url']; ?>" width="100%" class=" img-rounded screenshot">
	</div>
<?php endforeach; ?>