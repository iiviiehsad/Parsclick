<header class="clearfix">
	<section id="branding">
		<a href="/"><img src="<?php echo is_local() ? '' : '/'; ?>images/misc/logo.png" alt="Logo for Parsclick"></a>
		<img src="<?php echo is_local() ? '' : '/'; ?>images/misc/cursor.gif" class="pull-left" width="50" alt="cursor"
		     style="width:50px !important;">
	</section>
	<section class="navbar">
		<ul class="nav navbar-nav">
			<li class="<?php echo active(['member']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>member">
					<i class="fa fa-home fa-lg"></i> خانه
				</a>
			</li>
			<li class="<?php echo active(['member-profile', 'member-edit-profile']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>member-profile">
					<i class="fa fa-pencil-square fa-lg"></i> حساب کاربری
				</a>
			</li>
			<li class="dropdown <?php echo active(['member-courses', 'member-articles']); ?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-archive fa-lg"></i> محتوی
					<span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li class="<?php echo active(['member-courses']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : '/'; ?>member-courses">
							<i class="fa fa-film fa-lg"></i> دروس
						</a>
					</li>
					<li class="<?php echo active(['member-articles']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : '/'; ?>member-articles">
							<i class="fa fa-newspaper-o fa-lg"></i> مقالات
						</a>
					</li>
				</ul>
			</li>
			<li class="<?php echo active(['forum']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>forum">
					<i class="fa fa-comments fa-lg"></i> انجمن
				</a>
			</li>
			<li class="<?php echo active(['member-playlist']); ?>">
				<a href="<?php echo is_local() ? '' : '/'; ?>member-playlist">
					<i class="fa fa-bookmark fa-lg"></i> لیست پخش
				</a>
			</li>
			<li>
				<a href="<?php echo is_local() ? '' : '/'; ?>logout"><i class="fa fa-sign-out fa-lg"></i>
					خروج
				</a>
			</li>
		</ul>
	</section>
</header>