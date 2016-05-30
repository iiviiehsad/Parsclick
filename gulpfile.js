var elixir = require('laravel-elixir');

elixir.config.assetsPath = 'public_html/_/components';
elixir.config.publicPath = 'public_html/_';
elixir.config.appPath    = 'includes';

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
			// 'jquery-ui.css',
			'font-awesome.min.css',
			'sweetalert.css',
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
			'sweetalert.js',
			// 'jquery-ui.js',
			// 'jquery.countdown.min.js',
			'_myscript.js'
		])
	// TDD: type `gulp tdd` in command line to track your test
	// .phpSpec()
	// .phpUnit()
	;
});
