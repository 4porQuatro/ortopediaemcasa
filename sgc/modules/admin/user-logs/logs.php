<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');
	$user_id = $date = NULL;
	if(isset($_GET['user_id']) && isset($_GET['session_id'])){
		$user_id = $_GET['user_id'];
		$session_id = $_GET['session_id'];
		$result_logs = $mysqli->query("SELECT t1.*, t2.name FROM sgc_user_log AS t1, sgc_users AS t2 WHERE t1.user_id = t2.user_id AND t1.user_id = " . $user_id . " AND session_id = '" . $session_id . "' ORDER BY timestamp DESC;");
		if($result_logs){
			$lines_logs = $result_logs->fetch_object();
			// get last log
			$user_state = "offline";
			$result_last_log = $mysqli->query("SELECT timestamp FROM sgc_user_log WHERE user_id = " . $user_id . " ORDER BY timestamp DESC;");
			if($result_last_log->num_rows){
				$line_last_log = $result_last_log->fetch_object();
				if(strtotime($line_last_log->timestamp) > strtotime("-30 minutes")){
					$user_state = "online";
				}
			}
		}else{
			header("location: index.php");
			exit;
		}
	}else{
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
	    	<div class="record_options_pane">
	    		<a class="record_opt_btn" href="index.php">&larr; Voltar à listagem</a>
	        </div>
	    	<h2>Registo de Logs</h2>
	        <h3>Utilizador: <?= $lines_logs->name . ' [' . $user_state . ']'; ?></h3>
	        <div class="records_pane">
	        	<div class="results_pane">
	            	<?php
						if(!$result_logs->num_rows){
							echo
							'<p class="info">Não foram encontrados registos.</p>', PHP_EOL;
						}else{
					?>
	                <table class="records_list">
	                	<?php
	                    	do{
	                    ?>
	                    <tr>
	                        <td><a href="<?= $lines_logs->page; ?>" target="_blank"><?= $lines_logs->page; ?></a></td>
	                        <td style="width:120px;text-align:center"><a><?= $lines_logs->timestamp; ?></a></td>
	                    </tr>
	                    <?php
							}while($lines_logs = $result_logs->fetch_object());
						?>
	                </table>
	                <?php
						}
					?>
	            </div>
	        </div>
		</div>
		
		<?php $template->importScripts(); ?>
	</body>
</html>
