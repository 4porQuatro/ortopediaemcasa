<?php
	require_once('recordslist.class.php');
	
	if(isset($_POST['op'])){
		switch($_POST['op']){
			case 'set_folder':
				RecordsList::setFolder();
				break;
			case 'get_table_cols':
				RecordsList::getTableColumns();
				break;
			case 'delete':
				RecordsList::deleteRecord();
				break;
			case 'order':
				RecordsList::orderRecords();
				break;
			case 'import_records':
				RecordsList::importRecords();
				break;	
			default:
				die('<h3>ERROR: ' . $_POST['op'] . ' is not a valid operation!</h3>');
		}
	}
?>