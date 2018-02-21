<?php
	require_once('videosuploader.class.php');

	if(isset($_POST['op'])){
		switch($_POST['op']){
			case 'set_folder':
				VideosUploader::setFolder();
				break;
			case 'upload':
				VideosUploader::uploadFiles();
				break;
			case 'delete_file':
				VideosUploader::deleteFile();
				break;
			case 'update_order':
				VideosUploader::updateOrder();
				break;
			case 'update_title':
				VideosUploader::updateTitle();
				break;
			default:
				die('<h3>ERROR: ' . $_POST['op'] . ' is not a valid operation!</h3>');
		}
	}
?>
