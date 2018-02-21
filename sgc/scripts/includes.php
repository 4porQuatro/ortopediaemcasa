<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');
	require_once(SGC_ROOT . DS . 'scripts' . DS . 'user_log.php');
	require_once(SGC_ROOT . DS . 'scripts' . DS . 'permissions.php');
	require_once(SGC_ROOT . DS . 'scripts' . DS . 'utilities.php');

	if(isset($_SESSION['sgc_language_id'])){
		$language_id = $_SESSION['sgc_language_id'];
	}
?>
