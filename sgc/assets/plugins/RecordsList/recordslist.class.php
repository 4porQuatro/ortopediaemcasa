<?php
	class RecordsList{
		/*
		*	Set folder
		*/
		public static function setFolder(){
			$folder = dirname($_SERVER['DOCUMENT_ROOT']) . "/public/uploads/data/";

			if(!file_exists($folder)){
				mkdir($folder, 0755, true);
				//chmod($folder, 0755);
			}

			echo file_exists($folder);
		}

		/*
		*	Get table columns
		*/
		public static function getTableColumns(){
			require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');

			$table = $_POST['table'];
			$cols_arr = array();

			$result = $mysqli->query("SHOW COLUMNS FROM " . $table . ";");
			if($result->num_rows){
				while ($row = $result->fetch_assoc()){
					array_push($cols_arr, '"' . $row['Field'] . '"');
				}
			}

			echo json_encode($cols_arr);
		}

		/*
		*	Delete record
		*/
		public static function deleteRecord(){
			require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');

			if(isset($_POST['table']) && isset($_POST['primary_key']) && isset($_POST['id']) &&  isset($_POST['language_id']) && isset($_POST['sortable'])){
				$table = $_POST['table'];
				$primary_key = $_POST['primary_key'];
				$id = $_POST['id'];
				$language_id = $_POST['language_id'];
				$sortable = $_POST['sortable'];

				// set conditions
				$conditions = "";
				if($language_id){
					$conditions .= " AND language_id = " . $language_id;
				}

				// get record
				$result_record = $mysqli->query("SELECT * FROM " . $table . " WHERE " . $primary_key . " = " . $id . $conditions . ";") or die($mysqli->error);
				if($result_record->num_rows){
					$line_record = $result_record->fetch_object();

					if($mysqli->query("DELETE FROM " . $table. " WHERE " . $primary_key . " = " . $id . $conditions . ";") or die($mysqli->error)){
						echo '<p class="success"><b>O registo foi eliminado com sucesso!</b></p>';

						// if list is sortable, the priorities must be updated
						if(filter_var($sortable, FILTER_VALIDATE_BOOLEAN)){
							$mysqli->query("UPDATE " . $table . " SET priority = priority - 1 WHERE priority > " . $line_record->priority . $conditions . ";") or die($mysqli->error);
						}
					}
				}
			}else{
				die('<h3>Records List error!</h3><p>Insuficient data for delete operation!</p>');
			}
		}

		/*
		*	Order records
		*/
		public static function orderRecords(){
			require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');

			$table = $_POST['table'];
			$primary_key = $_POST['primary_key'];
			$ids_arr = $_POST['ids_arr'];
			$priorities_arr = $_POST['priorities_arr'];

			$conditions = "";
			if($_POST['language_id']){
				$conditions .= " AND language_id = " . $_POST['language_id'];
			}

			$mysqli->autocommit(false);

			for($i = 0; $i < sizeof($ids_arr); $i++){
				$mysqli->query("UPDATE " . $table . " SET priority = -priority WHERE " . $primary_key . " = " . $ids_arr[$i] . $conditions . ";");
			}

			for($i = 0; $i < sizeof($ids_arr); $i++){
				$mysqli->query("UPDATE " . $table . " SET priority = " . $priorities_arr[$i] . " WHERE " . $primary_key . " = " . $ids_arr[$i] . $conditions . ";");
			}

			if(!$mysqli->error){
				$mysqli->commit();
			}
		}

		/*
		*	Import records
		*/
		public static function importRecords(){
			require_once($_SERVER['DOCUMENT_ROOT'] . '/core/init.php');
			date_default_timezone_set('Europe/Lisbon');

			$errors = "";
			$allowed_formats = array("csv");

			$table = $_POST['table'];

			$file_name = $_FILES['csv_file']['name'];
			$file_size = $_FILES['csv_file']['size'];
			$extension = explode(".", $file_name);
			$file_extension = strtolower($extension[sizeof($extension) - 1]);
			$file = dirname($_SERVER['DOCUMENT_ROOT']) . "/public/uploads/data/" . $table . ".csv";

			if($file_size <= 0)
				$errors .= "O tamanho do documento deve ser maior que 0KB.<br>";
			if(!in_array($file_extension, $allowed_formats))
				$errors .= "O ficheiro não tem um formato válido.<br>";

			if(empty($errors)){
				move_uploaded_file($_FILES['csv_file']['tmp_name'], $file);

				if(($handle = fopen($file, "r")) !== FALSE){
					$row = 1;
					$values_arr = array();
					$import_errors = "";

					while(($data = fgetcsv($handle, 1000, ";")) !== FALSE){
						$num = count($data);

						if($row > 1){
							unset($data[sizeof($data) - 1]);

							foreach($data as $key=>$val){
								$data[$key] = "'" . $val . "'";
							}

							$mysqli->autocommit(false);
							$mysqli->query("INSERT INTO $table VALUES(" . implode(",", $data) . ")");

							if($mysqli->error){
								$mysqli->rollback();
								$import_errors .= ($row) . " ";
							}else{
								$mysqli->commit();
							}
						}

						$row++;
					}
					fclose($handle);

					if(!empty($import_errors)){
						echo 'Foram detetados erros nas seguintes linhas: ' . $import_errors;
					}
				}

				unlink($file);
			}else{
				echo $errors;
			}
		}
	}
?>
