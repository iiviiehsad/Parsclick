var elixir = require('laravel-elixir');

elixir(function(mix) {
  // mix.sass('app.scss' , "new_direcroty")
  // mix.less('app.less' , "new_direcroty")
  mix.less(['bootstrap.less', '_mystyles.less'], "_/components/css")
    .styles([
      'app.css',
      'bootstrap-rtl.min.css',
      'font-awesome.min.css'
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
			'modal.js',
			'popover.js',
			'scrollspy.js',
			'tab.js',
			'wow.min.js',
      '_myscript.js'
    ])
    // TDD: type `gulp tdd` in command line to track your test 
    // .phpSpec()
    // .phpUnit()
  ;
});
