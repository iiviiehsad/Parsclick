<aside class="socialmedia">

	<?php include("aside-twitter.php"); ?>
	<script>
		!function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
			if(!d.getElementById(id)) {
				js = d.createElement(s);
				js.id = id;
				js.src = p + '://platform.twitter.com/widgets.js';
				fjs.parentNode.insertBefore(js, fjs);
			}
		}(document, 'script', 'twitter-wjs');
	</script>
	<h2><i class="fa fa-rss-square fa-lg"></i> اخبار سایت</h2>
	<form class="form-horizontal" role="form" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=parsclick/HGms', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
		<div class="form-group">
			<input placeholder="ایمیل خود را برای اخبار سایت وارد کنید" type="text" name="email"/>
			<input type="hidden" value="parsclick/HGms" name="uri"/>
			<input type="hidden" name="loc" value="en_US"/>
			<input style="margin: 3px 0;" class="btn btn-primary btn-block" type="submit" value="ملحق شوید"/>
		</div>
	</form>

</aside><!--socialmedia-->