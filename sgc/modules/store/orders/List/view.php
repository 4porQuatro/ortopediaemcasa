<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "store_orders";
	$pk = "id";

	/*..........................................................................................................*/

	$sort_field = $pk;
	$sort_order = "DESC";

	$value = $filter1 = $filter2 = $filter3 = $filter4 = '%';
	if(isset($_POST['value']))
		$value = $_POST['value'];
	if(isset($_POST['filters_arr'])){
		$filter1 = $_POST['filters_arr'][0];
		$filter2 = $_POST['filters_arr'][1];
		$filter3 = $_POST['filters_arr'][2];
		$filter4 = $_POST['filters_arr'][3];
	}

	$result_records = $mysqli->query(
		"SELECT t1.*, t2.billing_name AS 'username', t3.title AS 'state'
		FROM " . $table . " AS t1, users AS t2, store_order_states AS t3
		WHERE t1.user_id = t2.id
		AND t1.state_id = t3.id
		AND YEAR(t1.created_at) LIKE '" . $filter3 . "'
		AND MONTH(t1.created_at) LIKE '" . $filter4 . "'
		AND t2.id LIKE '" . $filter1 . "'
		AND t3.id LIKE '" . $filter2 . "'
		AND t1." . $pk . " LIKE '%" . $value . "%'
		GROUP BY t1." . $pk . "
		ORDER BY t1.$sort_field $sort_order;"
	) or die($mysqli->error);

	if(!$result_records->num_rows){
		echo '<p class="info">Não foram encontrados registos</p>';
	}else{
		echo
		'<table class="records_list no_highlight">
			<tr>
				<th style="width:1%;">Nr.</th>
				<th style="width:16%">Data</th>
				<th>Utilizador</th>
				<th style="width:16%">Estado</th>
				<th style="width:1%">Custo</th>
			</tr>', PHP_EOL;

		$abs_total = 0;
		while($record = $result_records->fetch_object()){
			$link = "edit.php?edit_hash=" . md5($record->$pk);
			echo
			'<tr>
				<td style="text-align:center"><a href="' . $link . '">' . $record->$pk . '</a></td>
				<td style="text-align:center"><a href="' . $link . '">' . $record->created_at . '</a></td>
				<td style="text-align:center"><a href="' . $link . '">' . $record->username  . '</a></td>
				<td style="text-align:center"><a href="' . $link . '">' . $record->state . '</a></td>
				<td style="text-align:right"><a href="' . $link . '">' . number_format($record->total, 2, ',', '.')  . '€</a></td>
			</tr>', PHP_EOL;

			$abs_total += $record->total;
		}

		echo
		'	<tr>
				<th colspan="4" style="text-align:right">Total</th>
				<th style="text-align:right">' . number_format($abs_total, 2, ',', '.') . '€</th>
			</tr>
		</table>

		<hr class="hline">', PHP_EOL;
	}
?>
