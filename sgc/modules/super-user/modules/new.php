<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "sgc_modules";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha os campos de preenchimento obrigatório.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (title, active, created_at) VALUES(?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"si",
				$posts['title'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

			/*......................................................................................*/

			/*
			*	Tables
			*/
			if(isset($_POST['table_key'])){
				$stmt_insert_table = $mysqli->prepare("INSERT INTO sgc_modules_tables (priority, name, has_images, has_docs, has_videos, active, module_id, created_at) VALUES(?, ?, ?, ?, ?, ?, " . $fk_id . ", CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE name = ?") or die($mysqli->error);
				$stmt_insert_table->bind_param("isiiiis", $table_priority, $table_name, $table_has_images, $table_has_docs, $table_has_videos, $table_active, $table_name);

				foreach($_POST['table_key'] as $key=>$row_index){
					$table_priority = $row_index + 1;
					$table_name = $_POST['table_name'][$key];
					$table_has_images = isset($_POST['table_has_images'][$key]) ? 1 : 0;
					$table_has_docs = isset($_POST['table_has_docs'][$key]) ? 1 : 0;
					$table_has_videos = isset($_POST['table_has_videos'][$key]) ? 1 : 0;
					$table_active = isset($_POST['table_active'][$key]) ? 1 : 0;

					if(!empty($table_name)){
						$stmt_insert_table->execute() or die($stmt_insert_table->error);
					}
				}
			}

			/*............................................................................*/

			$mysqli->commit();
			header("location: index.php?insert=success");
			exit;
		}
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
	    		<a class="record_opt_btn" href="index.php">&larr; Cancelar</a>
	        </div>

	    	<h2>Novo registo</h2>

	        <?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram detetados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Tabelas</li>
	        </ul>
	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	        	<div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Título *</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
	                    </tr>
	                </table>

                    <table>
                        <tr>
                            <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Active</label></td>
                        </tr>
                    </table>
	            </div>

	            <div class="form_pane">
					<?php
						if(!isset($_POST['table_key'])){
							$_POST['table_key'][0] = 0;
							$_POST['table_name'][0] = '';
							$_POST['table_has_images'][0] = 0;
							$_POST['table_has_docs'][0] = 0;
							$_POST['table_has_videos'][0] = 0;
							$_POST['table_active'][0] = 0;
						}
					?>
	                <table>
						<tr>
							<th>Tabela</th>
							<th style="width: 10%; text-align: center;">Imagens</th>
							<th style="width: 10%; text-align: center;">Documentos</th>
							<th style="width: 10%; text-align: center;">Vídeos</th>
							<th style="width: 10%; text-align: center;">Ativo</th>
						</tr>
	                    <?php
							foreach($_POST['table_key'] as $key=>$value){
	                    ?>
	                    <tr class="table_row">
							<td>
								<select name="table_name[]">
									<option value="">Selecione...</option>
									<?php
                                        // get database
                                        $result_db = $mysqli->query("SELECT DATABASE()");
                                        $line_db = $result_db->fetch_row();

										$rs_tables = $mysqli->query("SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME IN ('language_id') AND TABLE_SCHEMA = '" . $line_db[0] . "'");
										if($rs_tables->num_rows){
											while($table_rec = $rs_tables->fetch_object()){
												$selected = (isset($_POST['table_name'][$key]) && $_POST['table_name'][$key] == $table_rec->TABLE_NAME) ? ' selected' : '';
									?>
									<option value="<?= $table_rec->TABLE_NAME ?>"<?= $selected; ?>><?= $table_rec->TABLE_NAME ?></option>
									<?php
											}
										}
									?>
								</select>
							</td>
	                        <td style="text-align: center">
								<input type="checkbox" name="table_has_images[<?= $key ?>]" value="1"<?php if(isset($_POST['table_has_images'][$key]) && $_POST['table_has_images'][$key] == 1) echo ' checked'; ?>>
	                        </td>
	                        <td style="text-align: center">
								<input type="checkbox" name="table_has_docs[<?= $key ?>]" value="1"<?php if(isset($_POST['table_has_docs'][$key]) && $_POST['table_has_docs'][$key] == 1) echo ' checked'; ?>>
	                        </td>
	                        <td style="text-align: center">
								<input type="checkbox" name="table_has_videos[<?= $key ?>]" value="1"<?php if(isset($_POST['table_has_videos'][$key]) && $_POST['table_has_videos'][$key] == 1) echo ' checked'; ?>>
	                        </td>
	                        <td style="text-align: center">
								<input type="checkbox" name="table_active[<?= $key ?>]" value="1"<?php if(isset($_POST['table_active'][$key]) && $_POST['table_active'][$key] == 1) echo ' checked'; ?>>
	                        </td>
	                        <td style="width:1%">
	                        	<input type="hidden" name="table_key[]" value="<?= $key; ?>">
	                        	<span class="inline_btn table_remove_row">Remove</span>
	                        </td>
	                    </tr>
	                    <?php
							}
	                    ?>
	                    <tr>
	                        <td colspan="4">
	                            <input type="button" class="inline_btn" id="table_add_btn" value="Add table">
	                        </td>
	                    </tr>
	                </table>
	            </div>

	            <input type="submit" value="Guardar">
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>

		<?php $template->importScripts(); ?>
	    <script src="../../../assets/js/dynamicFields.js"></script>
	    <script>
		    dynamicFields('#table_add_btn', '.table_remove_row', '.table_row');
		</script>
	</body>
</html>
