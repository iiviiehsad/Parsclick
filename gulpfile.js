var elixir = require('laravel-elixir');

elixir(function(mix) {
	// mix.sass('app.scss' , "new_direcroty")
	// mix.less('app.less' , "new_direcroty")
	mix.less([
			'bootstrap.less',
			'bootstrap-rtl.min.less',
			'_mystyles.less'
		], "public_html/_/components/css")
		.styles([
			'app.css',
			'font-awesome.min.css',
			'prism.css'
		])
		.scripts([
			'jquery.js',
			'affix.js',
			'transition.js',
			'tooltip.js',
			'alert.js',
			'button.js',
			'carousel.js',
			'collapse.js',
			'dropdown.js',
			'popover.js',
			'scrollspy.js',
			'modal.js',
			'tab.js',
			'prism.js',
			'wow.min.js',
			'youtubeplayer.js',
			// 'jquery.countdown.min.js',
			'_myscript.js'
		])
	// TDD: type `gulp tdd` in command line to track your test
	// .phpSpec()
	// .phpUnit()
	;
});
