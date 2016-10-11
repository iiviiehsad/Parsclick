<?php require_once('../../includes/initialize.php');
$session->confirm_admin_logged_in();
$author  = Admin::find_by_id($session->id);
$logfile = SITE_ROOT . DS . 'logs' . DS . 'log.txt';
if (isset($_GET['clear'])) {
	file_put_contents($logfile, '');
	// Add the first log entry
	log_action('Logs Cleared By User', '<span class="badge" style="float:right;">' . $author->full_name() . '</span>');
	redirect_to('logfile.php');
}
include_layout_template('admin_header.php');
include_layout_template('admin_nav.php');
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-list"></i> جزئیات ثبت</h2>
			<?php
			if (file_exists($logfile) && is_readable($logfile) && $handle = fopen($logfile, 'r')):  // read
				echo '<ul class="list-group" style="direction:ltr;">';
				while ( ! feof($handle)):
					$entry = fgets($handle);
					if (trim($entry) != ''):
						echo "<li class='list-group-item'>{$entry}</li>";
					endif;
				endwhile;
				echo '</ul>';
				fclose($handle);
			else:
				echo "Could not read from {$logfile}.";
			endif;
			?>
		</article>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside class="register">
			<h2><i class="fa fa-trash-o"></i> پاک کردن فایل ثبت</h2>
			<p>لطفا اگر تمایل به پاک کردن اطلاعات فایل ثبت دارید روی دگمه ی زیر کلیک کنید.</p>
			<a class="btn btn-danger confirmation" href="logfile.php?clear=true"><i class="fa fa-trash"></i>
				فایل ثبت را پاک کن </a>
		</aside>
	</section><!-- sidebar -->
<?php include_layout_template('admin_footer.php'); ?>