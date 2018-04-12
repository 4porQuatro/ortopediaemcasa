<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');

	$search_value = "%";
	$language_id = NULL;

	if(isset($_POST['table']))
		$table = $_POST['table'];
	if(isset($_POST['primary_key']))
		$primary_key = $_POST['primary_key'];
	if(isset($_POST['columns']))
		$columns_arr = $_POST['columns'];
	if(isset($_POST['search_value']))
		$search_value = $_POST['search_value'];
	if(isset($_POST['filters_arr']))
		$filters_arr = $_POST['filters_arr'];
	if(isset($_POST['filters_vals_arr']))
		$filters_vals_arr = $_POST['filters_vals_arr'];
	if(isset($_POST['language_id']))
		$language_id = $_POST['language_id'];
	if(isset($_POST['sort_field']))
		$sort_field = $_POST['sort_field'];
	if(isset($_POST['sort_order']))
		$sort_order = $_POST['sort_order'];
	if(isset($_POST['sortable']))
		$sortable = $_POST['sortable'];
	if(isset($_POST['removable']))
		$removable = $_POST['removable'];
	if(isset($_POST['edit_page']))
		$edit_page = $_POST['edit_page'];
	if(isset($_POST['extra_btns']))
		$extra_btns = $_POST['extra_btns'];
	if(isset($_POST['path']))
		$path = $_POST['path'];
	
	$import_data = (isset($_POST['import_data'])) ? $_POST['import_data'] : false;
	$export_data = (isset($_POST['export_data'])) ? $_POST['export_data'] : false;

	$conditions = "";
	$conditions_arr = array();

	if($language_id){
		$conditions_arr['language_id'] = $language_id;
	}
	if(isset($filters_arr) && sizeof($filters_arr)){
		for($i = 0; $i < sizeof($filters_arr); $i++){
			$conditions_arr[$filters_arr[$i]] = $filters_vals_arr[$i];
		}
	}
	$conditions_arr[$columns_arr[0]] = get_magic_quotes_gpc() ? stripslashes($search_value) : $search_value;
	$conditions_arr[$columns_arr[0]] = '%' . $mysqli->real_escape_string($search_value) . '%';

	if(sizeof($conditions_arr)){
		$i = 0;
		foreach($conditions_arr as $key=>$val){
			$conditions	.= " " . $key . " LIKE '" . $val . "'";
			$i++;
			if($i < sizeof($conditions_arr)){
				$conditions .= " AND";
			}
		}
	}

	$result = $mysqli->query("SELECT * FROM " . $table . " WHERE " . $conditions . " ORDER BY " . $sort_field . " " . $sort_order . ";") or die($mysqli->error);

	echo
	'<div class="list_header">', PHP_EOL;

	if($result->num_rows){
		echo
		'<p class="records_display">' . $result->num_rows . ' registos</p>', PHP_EOL;
	}

	echo
	'	<div class="options_pane">', PHP_EOL;

	if(filter_var($export_data, FILTER_VALIDATE_BOOLEAN)){
		echo
		'	<a class="sprite database download" href="' . $path . 'csv_file.php?table=' . $table . '" title="Exportar dados para ficheiro CSV"></a>', PHP_EOL;
	}
	if(filter_var($import_data, FILTER_VALIDATE_BOOLEAN)){
		echo
		'	<span class="sprite database upload" title="Importar dados a partir de ficheiro CSV"></span>', PHP_EOL;
	}

	echo
	'	</div>
	</div>', PHP_EOL;

	if(!$result->num_rows){
		echo
		'<p class="info"><b>Não existem registos</b></p>';
	}else{
		echo
		'<table class="records_list">';

		while($lines = $result->fetch_object()){
			$data_priority = (filter_var($sortable, FILTER_VALIDATE_BOOLEAN)) ? ' data-priority="' . $lines->priority . '"' : '';

			echo
			'<tr id="' . $lines->$primary_key . '"' . $data_priority . '>', PHP_EOL;

			foreach($columns_arr as $key=>$column){
				echo
				'<td><a href="' . $edit_page . '?edit_hash=' . md5($lines->$primary_key) . '">' . htmlentities($lines->$column, ENT_QUOTES | ENT_IGNORE, 'UTF-8') . '</a></td>', PHP_EOL;
			}

			echo
			'	<td class="op"><a href="' . $edit_page . '?edit_hash=' . md5($lines->$primary_key) . '" title="Editar registo"><i class="far fa-edit rl-op-btn"></i></a></td>', PHP_EOL;

			if(isset($extra_btns) && sizeof($extra_btns)){
				foreach($extra_btns as $btn){
					echo
				'<td class="op">
					<a class="rl-op-btn" href="' . $btn['page'] . '?edit_hash=' . md5($lines->$primary_key) . '" title="' . $btn['title'] . '">
						<i class="' . $btn['class'] . '"></i>
					</a>
				</td>', PHP_EOL;
				}
			}

			if(filter_var($removable, FILTER_VALIDATE_BOOLEAN)){
				echo
				'<td class="op"><span class="rl-op-btn rl-op-btn--delete" data-id="' . $lines->$primary_key . '" title="Eliminar registo"><i class="far fa-trash-alt"></i></span></td>', PHP_EOL;
			}

			echo
			'</tr>';
		}

		echo
		'</table>', PHP_EOL;
	}
?>
