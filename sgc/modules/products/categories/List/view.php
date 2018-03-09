<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$pag = $_SERVER['PHP_SELF'];
	$edit_page = "edit.php";
	$pag_elim = "index.php";

	$table = "items_categories";
	$pk = "id";

	/*....................................................................................................................*/

	//-->SET PRIORITY
	if(isset($_POST['priority_hash']) && isset($_POST['priority']) && isset($_POST['direction'])){
		$id = $_POST['priority_hash'];
		$priority = $_POST['priority'];
		$parent_id_clause = ($_POST['parent_id'] == NULL) ? "IS NULL" : "= " . $_POST['parent_id'];
		$direction = $_POST['direction'];

		$result_max_limit = $mysqli->query("SELECT MAX(priority) as max_limit FROM $table WHERE parent_id $parent_id_clause;") or die($mysqli->error);
		$lines_max_limit = $result_max_limit->fetch_object();
		$max_limit = $lines_max_limit->max_limit;

		if($direction == "up" && $priority > 1){
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1 WHERE priority = $priority - 1 AND parent_id $parent_id_clause;") or die($mysqli->error);
			$mysqli->query("UPDATE " . $table . " SET priority = priority - 1 WHERE " . $pk . " = " . $id . ";") or die($mysqli->error);
		}else if($direction == "down" && $priority < intval($max_limit)){
			$mysqli->query("UPDATE " . $table . " SET priority = priority - 1 WHERE priority = $priority + 1 AND parent_id $parent_id_clause;") or die($mysqli->error);
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1 WHERE " . $pk . " = " . $id . ";") or die($mysqli->error);
		}
	}

	//--> DELETE RECORD
	if(isset($_POST['delete_hash'])){
		$id = $_POST['delete_hash'];

		// get parent
		$result_parent = $mysqli->query("SELECT parent_id FROM $table WHERE " . $pk . " = " . $id . ";") or die($mysqli->error);
		$line_parent = $result_parent->fetch_object();

		$parent_id_clause = (!$line_parent->parent_id) ? "IS NULL" : "= " . $line_parent->parent_id;

		$result_max_limit = $mysqli->query("SELECT MAX(priority) as max_limit FROM $table WHERE parent_id $parent_id_clause;") or die($mysqli->error);
		$lines_max_limit = $result_max_limit->fetch_object();

		$max_limit = $lines_max_limit->max_limit;

		$result_priority = $mysqli->query("SELECT priority FROM $table WHERE " . $pk . " = " . $id . ";") or die($mysqli->error);
		$lines_priority = $result_priority->fetch_object();

		$priority = $lines_priority->priority;

		$mysqli->query("UPDATE " . $table . " SET priority = priority - 1 WHERE parent_id $parent_id_clause AND priority BETWEEN $priority AND $max_limit;") or die($mysqli->error);

		$mysqli->query("DELETE FROM $table WHERE " . $pk . " = " . $id . ";") or die($mysqli->error);
	}

	/*..........................................................................................................*/

	$arr = array();
	$result = $mysqli->query("SELECT * FROM " . $table . " WHERE language_id = " . $language_id . " ORDER BY priority ASC;");
	if(!$result->num_rows){
		echo '<p class="info">Não existem registos</p>';
	}else{
		while($row = $result->fetch_object()){
			$arr[$row->parent_id][$row->$pk] = $row;
		}
		printTree($mysqli, $arr, NULL, NULL, 0, $table, $edit_page, true);
	}

	if(isset($result_delete))
		echo '<p class="success">O registo foi eliminado com sucesso!</p>';
?>
<script>
var $delete_btns = $(".sprite.delete_record");
var priority_btns = document.getElementsByName("priority_btn");
var lines = $(".line");
$.each($delete_btns, function(){
	$(this).click(function(){
		deleteRecord($(this).data("id"));
	});
});
$.each(priority_btns, function(){
	$(this).click(function(){
		setPriority(this.id)
	});
});
$.each(lines, function(){
	$(this).mouseover(function(){
		$(this).toggleClass("hover_line");
	});
	$(this).mouseout(function(){
		$(this).toggleClass("hover_line");
	});
});
</script>
