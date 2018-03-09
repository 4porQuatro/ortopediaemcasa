<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<?php $template->importMetaTags(); ?>
		<?php $template->importHeadTitle(); ?>
		<?php $template->importStyles(); ?>
		<?php $template->importHeadScripts(); ?>
		<script src="List/controller.js"></script>
	</head>
	
	<body>
		<?php $template->printSideBar($mysqli); ?>

	    <div id="data_container">
	    	<div class="record_options_pane">
	    		<a class="record_opt_btn" href="new.php">Novo registo &rarr;</a>
	        </div>
	    	<h2>Lista de registos</h2>

	        <div id="results"></div>
	    </div>

		<?php $template->importScripts(); ?>
	</body>
</html>
