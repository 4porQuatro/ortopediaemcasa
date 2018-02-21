<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	/*..........................................................................................................*/

	$filter1 = $filter2 = '%';

	if(isset($_POST['filters_arr'])){
		$filter1 = $_POST['filters_arr'][0];
		$filter2 = $_POST['filters_arr'][1];
	}

	$result_records = $mysqli->query(
		"SELECT t1.*, t2.billing_name AS 'username', t3.title AS 'item'
		FROM stock_alerts AS t1, users AS t2, items AS t3
		WHERE t1.user_id = t2.id
		AND t1.item_id = t3.item_id
		AND t2.id LIKE '" . $filter1 . "'
		AND t3.item_id LIKE '" . $filter2 . "'
		ORDER BY t1.created_at DESC"
	) or die($mysqli->error);

	if(!$result_records->num_rows){
		echo '<p class="info">Não foram encontrados registos</p>';
	}else{
		echo
		'<table class="records_list no_highlight">
			<tr>
				<th style="width:1%;">Nr.</th>
				<th style="width:16%">Data</th>
				<th style="width:25%">Utilizador</th>
				<th>Item</th>
			</tr>', PHP_EOL;

		while($record = $result_records->fetch_object()){
			echo
			'<tr>
				<td style="text-align:center"><a>' . $record->id . '</a></td>
				<td style="text-align:center"><a>' . $record->created_at . '</a></td>
				<td style="text-align:center"><a>' . $record->username  . '</a></td>
				<td style="text-align:center"><a>' . $record->item . '</a></td>
			</tr>', PHP_EOL;
		}

		echo
		'</table>', PHP_EOL;
	}
?>
