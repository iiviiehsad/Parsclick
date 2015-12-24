<?php require_once("../includes/initialize.php");
$session->logout();
redirect_to("login");
if(isset($database)) { $database->close_connection(); }