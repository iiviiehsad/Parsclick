</div>
<section class="container">
	<footer class="row">
		<nav class="col-lg-12">
			<ul class="breadcrumb">
				<li class="pull-left arial" style="direction:ltr;">
					Copyright &copy; <?php echo strftime('%Y', time()); ?> Parsclick
				</li>
				<li><a href="<?php echo is_local() ? '../' : '/'; ?>login">قسمت اعضا</a></li>
			</ul>
		</nav>
	</footer>
</section>
</section>
<script src="<?php echo is_local() ? '../' : '/'; ?>_/js/all.js"></script>
</body>
</html>
<?php if (isset($database)) $database->close_connection(); ?>