</div><!-- content row -->
<section class="container">
	<footer class="row">
		<nav class="col-lg-12">
			<ul class="breadcrumb">
				<li class="pull-left arial" style="direction:ltr;">Copyright &copy; <?php echo strftime("%Y", time()); ?>
				                                                   Parsclick
				</li>
				<li><a href="../login">قسمت اعضا</a></li>
			</ul><!-- breadcrumb -->
		</nav><!-- nav -->
	</footer><!-- footer -->
</section><!-- footer container -->
</section><!-- container -->
<script src="../_/js/all.js"></script>
<?php active(); ?>
</body>
</html>
<?php if(isset($database)) $database->close_connection(); ?>