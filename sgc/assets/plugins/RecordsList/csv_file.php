<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');

	if(isset($_GET['table'])){
		$table = $_GET['table'];
		$filename = $table . "_" . date("Y-m-d_H\Hi");
		$csv_output = "";

		// get table columns
		$result = $mysqli->query("SHOW COLUMNS FROM " . $table . ";");
		if($result->num_rows){
			$i = 0;
			while ($row = $result->fetch_assoc()) {
				$csv_output .= $row['Field'] . ";";
				$i++;
			}
		}

		$csv_output .= "\n";

		$values = $mysqli->query("SELECT * FROM " . $table . ";");
		while($rowr = $values->fetch_row()){
			for($j = 0; $j < $i; $j++){
				$string = strip_tags($rowr[$j]);
				$string = str_replace(";", ",", $string);
				$csv_output .= $string . "; ";
			}
			$csv_output .= "\n";
		}

		header("Content-type: application/csv");
    	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
		print $csv_output;
		exit;
	}
?>
