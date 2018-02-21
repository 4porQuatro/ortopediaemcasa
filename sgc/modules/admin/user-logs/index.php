<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	use Carbon\Carbon;

	$user_id = (isset($_COOKIE['sgc_loglist_userid'])) ? $_COOKIE['sgc_loglist_userid'] : "NULL";

	if(isset($_GET['user_id'])){
		$user_id = $_GET['user_id'];
		setcookie("sgc_loglist_userid", $user_id, time() + 86400);
	}

	$result_logs = $mysqli->query("SELECT * FROM sgc_user_log WHERE user_id = " . $user_id . " GROUP BY session_id ORDER BY timestamp DESC;");
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
	        </div>
	    	<h2>Registo de Logs</h2>
	        <h3>Selecione um utilizador para consultar as suas sessões</h3>

	        <div class="records_pane">
	        	<table>
	            	<tr>
	                	<td>
	                        <select onChange="window.location=this.options[this.selectedIndex].value">
	                            <option value="index.php?user_id=NULL">Filtrar por utilizador</option>
	                            <?php
									$result = $mysqli->query("SELECT * FROM sgc_users ORDER BY name ASC;") or die($mysqli->error);
									if($result){
										while($lines = $result->fetch_object()){
											// get last log
											$user_state = "offline";
											$result_last_log = $mysqli->query("SELECT timestamp FROM sgc_user_log WHERE user_id = " . $lines->user_id . " ORDER BY timestamp DESC;");
											if($result_last_log->num_rows){
												$line_last_log = $result_last_log->fetch_object();
												if(strtotime($line_last_log->timestamp) > strtotime("-30 minutes")){
													$user_state = "online";
												}
											}

											$selected = ($lines->user_id == $user_id) ? 'selected' : '';
	                                ?>
	                            <option value="index.php?user_id=<?= $lines->user_id; ?>" <?= $selected; ?>><?= $lines->name . ' [' . $user_state . ']'; ?></option>
	                            <?php
										}
									}
								?>
	                        </select>
	                    </td>
	                </tr>
	            </table>

	            <div class="results_pane">
	            	<?php
						if(!$result_logs->num_rows){
							echo
							'<p class="info">Não foram encontrados registos.</p>', PHP_EOL;
						}else{
					?>
	            	<div class="list_header">
	                	<p class="records_display"><?= $result_logs->num_rows; ?> sessões</p>
	                </div>

	                <table class="records_list">
	                    <?php
	                        while($lines_logs = $result_logs->fetch_object()){
	                            $date_arr = explode(" ", $lines_logs->timestamp);
	                            $date = $date_arr[0];
	                    ?>
	                    <tr>
	                        <td><a href="logs.php?user_id=<?= $lines_logs->user_id ?>&session_id=<?= $lines_logs->session_id ?>"><?= Carbon::createFromFormat('Y-m-d H:i:s', $lines_logs->timestamp) ?></a></td>
	                        <td style="width:300px;text-align:center"><a href="logs.php?user_id=<?= $lines_logs->user_id; ?>&session_id=<?= $lines_logs->session_id; ?>">Id da sessão: <?= $lines_logs->session_id; ?></a></td>
	                    </tr>
	                    <?php
	                        }
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
