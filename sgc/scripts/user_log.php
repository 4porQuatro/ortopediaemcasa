<?php
	if(isset($_SESSION['sgc_user_id'])){
		$page = $_SERVER['REQUEST_URI'];

		$current_page = false;

		// check if page has changed
		$result = $mysqli->query("SELECT * FROM sgc_user_log WHERE user_id = " . $_SESSION['sgc_user_id'] . " AND timestamp > DATE_SUB(NOW(),INTERVAL 5 MINUTE) ORDER BY timestamp DESC LIMIT 0, 1;");
		if($result->num_rows){
			$line = $result->fetch_object();

			if($line->page == $page){
				$mysqli->query("UPDATE sgc_user_log SET timestamp = NOW() WHERE log_id = " . $line->log_id . ";");
				$current_page = true;
			}
		}

		if(!$current_page){
			$mysqli->query("INSERT INTO sgc_user_log (page, session_id, user_id) VALUES ('$page', '" . session_id() . "', " . $_SESSION['sgc_user_id'] . ");");
		}
	}
?>
