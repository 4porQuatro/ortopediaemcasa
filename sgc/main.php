<?php
	require_once('core/init.php');

	if(!isset($_SESSION['sgc_permissions_arr'])){
		header("location: index.php");
		exit;
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<?php $template->importMetaTags(); ?>
		<?php $template->importHeadTitle(); ?>
		<?php $template->importStyles(); ?>
		<?php $template->importHeadScripts(); ?>
	</head>
	<body>
		<?php $template->printSideBar($mysqli); ?>

	    <div id="data_container">
	    </div>

		<?php $template->importScripts(); ?>
	</body>
</html>
