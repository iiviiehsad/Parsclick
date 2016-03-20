<?php
if(isset($_SERVER['HTTP_REFERER'])) {
	header("Location: " . $_SERVER["HTTP_REFERER"]);
	exit;
} else {
	header("Location: ../index.php");
	exit;
}
?>