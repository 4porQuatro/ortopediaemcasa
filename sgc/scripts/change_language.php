<?php
	if(!isset($_SESSION)) session_start();
	if(isset($_POST['op']) && $_POST['op'] == "change_language"){
		$_SESSION['sgc_language_id'] = $_POST['language_id'];
	}

	header("location: " . $_SERVER['HTTP_REFERER']);
	exit;
?>
