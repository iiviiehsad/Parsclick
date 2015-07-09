<?php
require_once("../includes/initialize.php");
$session->logout();
redirect_to("login.php");
if(isset($database)) { $database->close_connection(); }
?>