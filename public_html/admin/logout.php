<?php require_once('../../includes/initialize.php');
$session->logout();
redirect_to(is_local() ? 'index.php' : '/admin');