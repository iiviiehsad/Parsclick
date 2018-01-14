<header class="clearfix">

	<section id="branding">
		<a href="/">
			<img src="<?php echo is_local() ? '' : '/'; ?>images/misc/logo.png"
			     alt="Logo for Parsclick" title="www.parsclick.net">
		</a>
	</section>

	<section class="navbar">
		<ul class="nav navbar-nav">
			<li class="<?php echo active(['index']); ?>">
				<a href="<?php echo is_local() ? 'index.php' : '/'; ?>"> خانه</a>
			</li>
			<li class="<?php echo active(['articles']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>articles.php">مقالات</a>
			</li>
			<li class="<?php echo active(['courses']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>courses.php">دروس</a>
			</li>
			<li class="<?php echo active(['anjoman']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>anjoman.php">انجمن</a>
			</li>
			<li class="<?php echo active(['faq']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>faq.php"> سوالات شما</a>
			</li>
			<li class="<?php echo active(['help']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>help.php"> کمک به ما</a>
			</li>
			<li class="<?php echo active(['login']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>login.php"> ورود</a></li>
		</ul>
	</section>
	<section id="modal" class="modal fade">
		<div class="modal-body">
			<img id="modalimage" src="" alt="Modal Photo">
		</div>
	</section>
</header>
