<header class="clearfix">
	<section id="branding">
		<a href="index.php"><img src="<?php echo is_local() ? '../' : '/'; ?>images/misc/admin-area.png"
		                         alt="Logo for Admin Area"></a>
	</section>
	<section class="navbar">
		<ul class="nav navbar-nav">
			<li class="<?php echo active(['admin']); ?>">
				<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>admin.php"><i class="fa fa-home fa-lg"></i> خانه</a>
			</li>

			<li class="dropdown
			<?php echo active([
					'member_list',
					'new_member',
					'edit_member',
					'email_to_members',
			]); ?>
			">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-users fa-lg"></i>
					اعضا<span class="caret"></span>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li class="<?php echo active(['member_list', 'new_member', 'edit_member']); ?>">
						<a href="<?php echo is_local() ? '' : ADMIN_DIR; ?>member_list.php"> لیست عضوها</a>
					</li>
					<li class="<?php echo active(['email_to_members']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>email_to_members.php"> ایمیل به عضوها</a>
					</li>
				</ul>
			</li>

			<li class="dropdown
			<?php echo active([
					'admin_list',
					'author_list',
					'new_admin',
					'new_author',
					'edit_admin',
					'edit_author',
					'email_to_authors',
			]); ?>
			">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-lg"></i>
					کارکنان<span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li class="<?php echo active(['admin_list', 'new_admin', 'edit_admin']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>admin_list.php"> لیست مدیران</a>
					</li>
					<li class="<?php echo active(['author_list', 'new_author', 'edit_author']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>author_list.php"> لیست نویسندگان</a>
					</li>
					<li class="<?php echo active(['email_to_authors']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>email_to_authors.php"> ایمیل به نویسندگان</a>
					</li>
				</ul>
			</li>
			<li class="dropdown
			<?php echo active([
					'admin_courses',
					'admin_articles',
					'new_category',
					'new_subject',
					'edit_category',
					'edit_subject',
					'new_article',
					'new_course',
					'edit_article',
					'edit_course',
			]); ?>
			">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-th-list fa-lg"></i> محتوی <span class="caret"></span>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li class="<?php echo active(['admin_courses', 'new_category', 'edit_category', 'edit_course']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>admin_courses.php">
							<i class="fa fa-film fa-lg fa-lg"></i> دروس
						</a>
					</li>
					<li class="<?php echo active(['admin_articles', 'new_subject', 'edit_subject', 'edit_article']); ?>">
						<a tabindex="-1" href="<?php echo is_local() ? '' : ADMIN_DIR; ?>admin_articles.php">
							<i class="fa fa-newspaper-o fa-lg"></i> مقالات
						</a>
					</li>
				</ul>
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
