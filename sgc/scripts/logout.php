<?php
	if(!isset($_SESSION)) session_start();

	unset($_SESSION['sgc_user_id']);
	unset($_SESSION['sgc_username']);
	unset($_SESSION['sgc_super_user']);
	unset($_SESSION['sgc_permissions_arr']);
	unset($_SESSION['sgc_language_id']);

	unset($_SESSION['KCFINDER']);

	header("location: /index.php");
	exit;
?>
