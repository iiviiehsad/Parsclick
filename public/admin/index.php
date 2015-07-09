<?php require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
if($session->is_admin_logged_in()) {
	redirect_to("admin.php");
} elseif($session->is_author_logged_in()) {
	redirect_to("author.php");
}
if(isset($_POST["submit"])) {
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);
	$type     = "";
	if(isset($_POST["type"])) {
		$type = trim($_POST["type"]);
	}
	// check the database to see if username or password exist first
	$found_admin  = Admin::authenticate($username, $password);
	$found_author = Author::authenticate($username, $password);
	// TRY TO LOGIN
	if($found_admin && ($type === "admin")) { //find the super admin 1st
		$session->admin_login($found_admin);
		log_action("Login", "<span class='alert-danger'>" . ucfirst($found_admin->username) . "</span> logged in.");
		redirect_to("admin.php");
	} elseif($found_author && ($type === "author")) { // find the authors 2nd
		$session->author_login($found_author);
		log_action("Login", "<span class='alert-success'>" . ucfirst($found_author->username) . "</span> logged in.");
		redirect_to("author.php");
	} else {
		$errors = "اسم کاربری و یا پسورد اشتباه است!";
	}
} else { // form has not been submitted
	$username = "";
	$password = "";
	$errors   = "";
}
?>
<?php include_layout_template("admin_header.php"); ?>
	<header class="clearfix">
		<section id="branding">
			<a href="index.php"><img src="../images/misc/admin-area.png" alt="Logo for Admin Area"></a>
		</section>
	</header>
	<hr/>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<?php include "../_/components/php/admin_login.php"; ?>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	</section><!-- sidebar -->
<?php include_layout_template("admin_footer.php"); ?>