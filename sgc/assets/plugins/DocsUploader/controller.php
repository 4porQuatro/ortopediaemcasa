<?php
	require_once('docsuploader.class.php');

	if(isset($_POST['op'])){
		switch($_POST['op']){
			case 'set_folder':
				DocsUploader::setFolder();
				break;
			case 'upload':
				DocsUploader::uploadFiles();
				break;
			case 'delete_file':
				DocsUploader::deleteFile();
				break;
			case 'update_order':
				DocsUploader::updateOrder();
				break;
			case 'update_title':
				DocsUploader::updateTitle();
				break;
			default:
				die('<h3>ERROR: ' . $_POST['op'] . ' is not a valid operation!</h3>');
		}
	}
?>
