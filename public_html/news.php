<?php require_once '../includes/initialize.php';

$bbc_rss        = parse_rss_feed('http://feeds.bbci.co.uk/persian/indepth/cluster_ptv_click/rss.xml');
$radiofarda_rss = parse_rss_feed('http://www.radiofarda.com/api/z_oqmergq_');
$dw_rss         = parse_rss_feed('http://partner.dw.com/syndication/feeds/rss-per_bloghaus_volltext.5722-mrss.xml');
$voa_rss        = parse_rss_feed('http://ir.voanews.com/api/zyupoeqpmi');
?>
<?php include_layout_template('header.php'); ?>
<?php $session->is_logged_in() ? include_layout_template('member_nav.php') : include_layout_template('nav.php'); ?>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2 class="bbcnassim">پرشین کلیک</h2>
		<div class="list-group">
			<?php
			foreach ($bbc_rss->channel->item as $item) : ?>
				<a target="_blank" class="bbcnassim list-group-item" href="<?php echo $item->link; ?>"
				   title="<?php echo $item->description; ?>"
				   data-toggle="tooltip"><?php echo $item->title; ?></a>
			<?php endforeach; ?>
		</div>
	</aside>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2 class="bbcnassim">رادیو فردا </h2>
		<div class="list-group">
			<?php
			foreach ($radiofarda_rss->channel->item as $item) : ?>
				<a target="_blank" class="bbcnassim list-group-item" href="<?php echo $item->link; ?>"
				   title="<?php echo $item->description; ?>"
				   data-toggle="tooltip">
					<img src="<?php echo $item->enclosure['url']; ?>" width="25%" class="pull-left img-rounded screenshot">
					<?php echo $item->title; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</aside>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2 class="bbcnassim">صدای آلمان </h2>
		<div class="list-group">
			<?php
			foreach ($dw_rss->channel->item as $item) : ?>
				<a target="_blank" class="bbcnassim list-group-item" href="<?php echo $item->link; ?>">
					<?php echo $item->title; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</aside>
</section>
<section class="sidebar col-sm-12 col-md-3 col-lg-3">
	<aside>
		<h2 class="bbcnassim">صدای آمریکا </h2>
		<div class="list-group">
			<?php
			foreach ($voa_rss->channel->item as $item) : ?>
				<a target="_blank" class="list-group-item bbcnassim" href="<?php echo $item->link; ?>"
				   title="<?php echo $item->description; ?>"
				   data-toggle="tooltip">
					<img src="<?php echo $item->enclosure['url']; ?>" width="25%" class="pull-left img-rounded screenshot">
					<?php echo $item->title; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</aside>
	<?php include_layout_template('aside-ad.php'); ?>
</section>
<script>
	(function() {
		setTimeout(function() {
			location.reload();
		}, 30000);
	})();
</script>
<?php include_layout_template('footer.php'); ?>

