<?php
	require_once('imagesuploader.class.php');
	
	if(isset($_POST['op'])){
		switch($_POST['op']){
			case 'set_folder':
				ImagesUploader::setFolder();
				break;
			case 'upload':
				ImagesUploader::uploadFiles();
				break;
			case 'delete_file':
				ImagesUploader::deleteFile();
				break;
			case 'update_order':
				ImagesUploader::updateOrder();
				break;
			case 'update_title':
				ImagesUploader::updateTitle();
				break;
			default:
				die('<h3>ERROR: ' . $_POST['op'] . ' is not a valid operation!</h3>');
		}
	}
?>