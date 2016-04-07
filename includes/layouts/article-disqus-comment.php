<article id="comments">
	<h2>نظرات</h2>
	<?php
	if($current_article) {
		$identifier = 'subject' . $current_article->subject_id . 'article' . $current_article->id;
	} elseif($current_course) {
		$identifier = 'category' . $current_article->category_id . 'course' . $current_course->id;
	} else {
		$identifier = '';
	}
	?>
	<div id="disqus_thread"></div>
	<script>
		var disqus_config = function() {
			//this.page.url        = "<?php //echo DOMAIN . $_SERVER['REQUEST_URI']; ?>";
			this.page.identifier = "<?php echo $identifier; ?>";
		};
		(function() { // DON'T EDIT BELOW THIS LINE
			var d = document, s = d.createElement('script');
			s.src = 'http://parsclick.disqus.com/embed.js';
			s.setAttribute('data-timestamp', +new Date());
			(d.head || d.body).appendChild(s);
		})();
	</script>
	<noscript>لطفا جاوااسکریپت رو فعال کنید برای دیدن
		<a href="https://disqus.com/?ref_noscript" rel="nofollow">نظرات</a>
	</noscript>
	<script id="dsq-count-scr" src="//parsclick.disqus.com/count.js" async></script>
</article>