<?php
global $current_article;
global $current_course;
if($current_article) {
	$identifier = 'sub' . $current_article->subject_id . 'art' . $current_article->id;
} elseif($current_course) {
	$identifier = 'cat' . $current_course->category_id . 'cou' . $current_course->id;
} else {
	$identifier = '';
}
// var_dump($identifier);
?>
<article id="comments">
	<h2>نظرات</h2>
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