<article id="comments">
	<h2>نظرات</h2>
	<div id="disqus_thread"></div>
	<script>
		var disqus_config = function () {
			this.page.url = <?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>; // Replace PAGE_URL with your page's canonical URL variable
			this.page.identifier = <?php echo $_SERVER['REQUEST_URI']; ?>; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
		};
		(function () { // DON'T EDIT BELOW THIS LINE
			var d = document, s = d.createElement('script');
			s.src = 'http://parsclick.disqus.com/embed.js';
			s.setAttribute('data-timestamp', +new Date());
			(d.head || d.body).appendChild(s);
		})();
	</script>
	<noscript>Please enable JavaScript to view the
		<a href="https://disqus.com/?ref_noscript" rel="nofollow">comments</a>
	</noscript>
	<!-- TODO ADD THIS TO BOTTOM OF YOUR PAGE NOT HERE IN YOUR FOOTER -->
	<script id="dsq-count-scr" src="//parsclick.disqus.com/count.js" async></script>
</article>
