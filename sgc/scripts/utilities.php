<?php
	#this function gets an index an returns the correspondent color
	function getColor($i){
		$colors = array("e1e1e1", "d1d1d1", "c1c1c1","b1b1b1" );
		$size = sizeof($colors);

		while($i >= $size){
			$i -= $size;
		}

		return "#" . $colors[$i];
	}

	function getUserIP(){
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}else if(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}else{
			$ip = $remote;
		}

		return $ip;
	}

	#this function prints a categories tree with edit and delete features
	function printTree(MySQLi $mysqli, array $arr, $parent = NULL, $language_id = NULL, $level = 0, $table, $edit_page, $order_col){
		$parent_id_clause = ($parent == NULL) ? "IS NULL" : "= " .$parent;

		if($language_id){
			$result_max_limit = $mysqli->query("SELECT MAX(priority) as max_limit FROM $table WHERE parent_id $parent_id_clause AND language_id = $language_id;") or die($mysqli->error);
		}else{
			$result_max_limit = $mysqli->query("SELECT MAX(priority) as max_limit FROM $table WHERE parent_id $parent_id_clause;") or die($mysqli->error);
		}
		$lines_max_limit = $result_max_limit->fetch_assoc();
		$max_limit = $lines_max_limit['max_limit'];

		$display = (!$order_col && $level > 0) ? "none" : "block";
		$bg_color = getColor($level);
		$margin_style = ($level == 0) ? "margin-left:0px;" : "";

		echo
		'<ul class="tree_list" style="display:' . $display . '; background-color:' . $bg_color . '; ' . $margin_style . '">';

		foreach($arr[$parent] as $id=>$val){
			$active = ($val->active) ? "ativa" : "inativa";

			echo
			'<li>
				<table>
					<tr class="line">
						<td style="text-align:left;">';

			if(isset($arr[$id]) && !$order_col)
				echo '<span class="line_toggler">+</span>';
			else
				echo '<span style="width:23px; display:inline-block;"></span>';

			echo
				'			<a href="' . $edit_page . '?edit_hash=' . md5($id) . '" title="editar">' . $val->title . '</a>
						</td>

						<td style="width:100px;text-align:center">' . $active . '</td>';


			if($order_col){
				echo
				'		<td style="width:55px;text-align:center">';

				if($val->priority != 1)
					echo
						'		<img src="/assets/img/Buttons/[btn]move_up.png" name="priority_btn" id="' . $id . ';' . $val->priority . ';' . $parent . ';up" alt="subir posi&ccedil;&atilde;o" title="subir posi&ccedil;&atilde;o">';
				if($val->priority != $max_limit)
					echo
						'		<img src="/assets/img/Buttons/[btn]move_down.png" name="priority_btn" id="' . $id . ';' . $val->priority . ';' . $parent . ';down" alt="descer posi&ccedil;&atilde;o" title="descer posi&ccedil;&atilde;o">';

				echo
					'	</td>';
			}

			echo
				'		<td style="width:45px"><a class="sprite btn24x24 edit_record" href="' . $edit_page . '?edit_hash=' . md5($id) . '" title="Editar registo"></a></td>
						<td style="width:45px"><span class="sprite btn24x24 delete_record" data-id="' . $id . '" title="Eliminar registo"></span></td>';


			echo
			'		</tr>
				</table>';

			if(isset($arr[$id])) printTree($mysqli, $arr, $id, $language_id, $level + 1, $table, $edit_page, $order_col);
		}

		echo
		'	</li>
		</ul>';
	}

	#this function prints the options fields for a select input with the categories tree
	function printTreeOptions(array $arr, $parent = NULL, $level = 0){
		$indent = "&nbsp;&nbsp;&nbsp;&nbsp;";

		foreach($arr[$parent] as $id => $val){
			echo '<option value="', $id, '">', str_repeat($indent, $level), $val, '</option>', PHP_EOL;
			if(isset($arr[$id])) printTreeOptions($arr, $id, $level + 1);
		}
	}
	#this function prints the options fields for a select input with the categories tree
	function printTreeOptionsSelection(array $arr, $parent = NULL, $level = 0, $selected_id){
		$indent = "&nbsp;&nbsp;&nbsp;&nbsp;";

		foreach($arr[$parent] as $id => $val){
			$selected_attr = ($id == $selected_id) ? 'selected="selected"' : '';
			echo '<option value="', $id, '" ' . $selected_attr . '>', str_repeat($indent, $level), $val, '</option>', PHP_EOL;
			if(isset($arr[$id])) printTreeOptionsSelection($arr, $id, $level + 1, $selected_id);
		}
	}

	function createSlug($string, $table, $mysqli){
		$slug = str_slug(strip_tags($string));
		$suffix = 1;

		$result = $mysqli->query("SELECT slug FROM $table WHERE slug = '$slug';") or die($mysqli->error);
		while($result->num_rows){
			$slug = str_slug($string . $suffix);
			$suffix++;

			$result = $mysqli->query("SELECT slug FROM $table WHERE slug = '$slug';") or die($mysqli->error);
		}

		return $slug;
	}
?>
