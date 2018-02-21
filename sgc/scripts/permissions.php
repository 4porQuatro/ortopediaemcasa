<?php
	if(!isset($_SESSION)) session_start();

	if(isset($_SESSION['sgc_permissions_arr'])){
		$flag = false;
		$current_page = $_SERVER['PHP_SELF'];

		foreach($_SESSION['sgc_permissions_arr'][NULL] as $parent=>$arr){
			if(isset($_SESSION['sgc_permissions_arr'][$arr->menu_id])){
				foreach($_SESSION['sgc_permissions_arr'][$arr->menu_id] as $id=>$val){
					if(strpos($current_page, $arr->folder . '/' . $val->folder) !== false){
						$flag = true;
					}
				}
			}
		}

		if(!$flag){
			header("location: /");
			exit;
		}
	}else{
		header("location: /");
		exit;
	}
?>
