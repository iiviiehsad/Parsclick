<header class="clearfix">

	<section id="branding">
		<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>">
			<img src="<?php echo is_local() ? '../' : '/'; ?>images/misc/admin-area.png" alt="Logo for Admin Area">
		</a>
	</section>

	<section class="navbar">
		<ul class="nav navbar-nav">
			<li class="<?php echo active(['author']); ?>">
				<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>author.php">
					<i class="fa fa-home fa-lg"></i> خانه
				</a>
			</li>
			<li class="<?php echo active(['author_profile', 'author_edit_profile']); ?>">
				<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>author_profile.php">
					<i class="fa fa-pencil-square fa-lg"></i> حساب کاربری
				</a>
			</li>
			<li class="dropdown
			<?php echo active([
					'author_courses',
					'author_articles',
					'new_course',
					'new_article',
					'author_edit_course',
					'author_edit_article'
			]); ?>
			">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-th-list fa-lg"></i> محتوی<span class="caret"></span>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li class="<?php echo active(['author_courses', 'new_course', 'author_edit_course',]); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>author_courses.php">
							<i class="fa fa-film fa-lg"></i> دروس
						</a>
					</li>
					<li class="<?php echo active(['author_articles', 'new_article', 'author_edit_article']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>author_articles.php">
							<i class="fa fa-newspaper-o fa-lg"></i> مقالات
						</a>
					</li>
				</ul>
			</li>
			<li class="<?php echo active(['author_contact']); ?>">
				<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>author_contact.php">
					<i class="fa fa-envelope fa-lg"></i> ارتباط با همکاران
				</a>
			</li>
			<li>
				<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>logout.php">
					<i class="fa fa-sign-out fa-lg"></i> خروج
				</a>
			</li>
		</ul>
	</section>

	<section id="modal" class="modal fade">
		<div class="modal-body">
			<img id="modalimage" src="" alt="Modal Photo">
		</div>
	</section>

</header>
