<article id="comments">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>نظرات</h2>
		</div>
		<div class="panel-body">
			<div id="fb-root"></div>
		</div>
		<script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if(d.getElementById(id)) return;
				js     = d.createElement(s);
				js.id  = id;
				js.src = "//connect.facebook.net/fa_IR/sdk.js#xfbml=1&version=v2.3";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		<div class="fb-comments" data-href="<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>" data-numposts="3" data-width="100%" data-order-by="reverse_time" data-colorscheme="light"></div>
</article>
