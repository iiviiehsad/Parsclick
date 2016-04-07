<header class="clearfix">
	<section id="branding">
		<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Admin Area"></a>
	</section>
	<!-- branding -->
	<section class="navbar">
		<ul class="nav navbar-nav">
			<li><a href="admin.php"><i class="fa fa-home fa-lg"></i> خانه</a></li>

			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-lg"></i>
					اعضا<span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li><a href="member_list.php"> لیست عضوها</a></li>
					<li><a tabindex="-1" href="email_to_members.php"> ایمیل به عضوها</a></li>
				</ul>
			</li>

			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-lg"></i>
					کارکنان<span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li><a tabindex="-1" href="admin_list.php"> لیست مدیران</a></li>
					<li><a tabindex="-1" href="author_list.php"> لیست نویسندگان</a></li>
					<li><a tabindex="-1" href="email_to_authors.php"> ایمیل به نویسندگان</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-th-list fa-lg"></i> محتوی
					<span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li><a tabindex="-1" href="admin_courses.php"><i class="fa fa-film fa-lg fa-lg"></i> دروس</a></li>
					<li><a tabindex="-1" href="admin_articles.php"><i class="fa fa-newspaper-o fa-lg"></i> مقالات</a></li>
				</ul>
			</li>
			<li><a href="logout.php"><i class="fa fa-sign-out fa-lg"></i> خروج</a></li>
		</ul><!-- nav -->
	</section><!-- navbar -->
	<!-- Modal -->
	<section id="modal" class="modal fade">
		<div class="modal-body">
			<img id="modalimage" src="" alt="Modal Photo">
		</div><!-- modal-body -->
	</section><!-- modal -->
</header><!-- header -->
