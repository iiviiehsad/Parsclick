<aside class="clearfix center">
	<span class="share-buttons">
		<!--Facebook -->
		<a href="http://www.facebook.com/sharer.php?u=
	<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>&t=<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>" target="_blank" onclick="shareButton(this); return false;" title="اشتراک گذاری در فیسبوک" data-toggle="tooltip" data-placement="bottom">
			<i class="fa fa-facebook-square fa-2x"></i>
		</a>
		<!--Twitter -->
		<a href="https://twitter.com/intent/tweet?source=<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>&amp;text=<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>&amp;via=AmirHassanAzimi&amp;name=Parsclick&amp;hashtags=Parsclick"
		   target="_blank" title="اشتراک گذاری در توییتر" data-toggle="tooltip" data-placement="bottom">
			<i class="fa fa-twitter-square fa-2x"></i>
		</a>
		<!--Google+ -->
		<a href="https://plus.google.com/share?url=
	<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>" target="_blank" onclick="shareButton(this); return false;" title="اشتراک گذاری در گوگل پلاس" data-toggle="tooltip" data-placement="bottom">
			<i class="fa fa-google-plus-square fa-2x"></i>
		</a>
		<!--LinkedIn -->
		<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=
	<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>&summary=&source=<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>" target="_blank" onclick="shareButton(this); return false;" title="اشتراک گذاری در لینکداین" data-toggle="tooltip" data-placement="bottom">
			<i class="fa fa-linkedin-square fa-2x"></i>
		</a>
	</span>
</aside>
