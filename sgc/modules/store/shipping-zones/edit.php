<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "store_shipping_zones";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash']);

	if(!$entity->getDBValue($pk)){
		header("location: index.php");
		exit;
	}


	if(isset($_POST['op']) && $_POST['op'] == "update"){

		// map posts

		$posts = $entity->mapPosts();



		// validate

		$errors = "";



		if(!$entity->checkRequiredFields()){

			$errors .= "<br>Preencha todos os campos obrigatórios.";

		}



		if(empty($errors)){

			$mysqli->autocommit(false);



			// update record

			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ? WHERE " . $pk . " = " . $entity->getDBValue($pk)) or die('<h3>Preparing statement...</h3>' . $mysqli->error);

			$stmt_update->bind_param(

				"s",

				$posts['name']

			);

			$stmt_update->execute() or die('<h3>Updating record...</h3>' . $stmt_update->error);



			/*......................................................................................*/



			/*

			*	Delete related countries

			*/

			$mysqli->query("DELETE FROM store_shipping_countries WHERE zone_id = " . $entity->getDBValue($pk) . " AND shipping_method_id = " . $entity->getDBValue('shipping_method_id')) or die($mysqli->error);



			/*

			 *	Insert related countries

			 */

			if(isset($_POST['country_id'])){

				$stmt_insert_country = $mysqli->prepare("INSERT INTO store_shipping_countries (zone_id, shipping_method_id, country_id, created_at) VALUES (" . $entity->getDBValue($pk) . ", " . $entity->getDBValue('shipping_method_id') . ", ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE zone_id = " . $entity->getDBValue($pk)) or die($mysqli->error);

				$stmt_insert_country->bind_param(

					"i",

					$id

				);



				foreach($_POST['country_id'] as $id=>$val){

					if(isset($_POST['country_id'][$id])){

						$stmt_insert_country->execute() or die($stmt_insert_country->error);

					}

				}

			}



			/*......................................................................................*/



			$mysqli->commit();

			header("location: index.php?edit=success");

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

	    	<h2>Editar registo nr.º <?= $entity->getDBValue($pk); ?></h2>

			<?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Países</li>
	        </ul>
	        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Nome*</th>
	                        <th style="width:25%">Método de envio</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="name" maxlength="<?= $entity->maxlen("name"); ?>" value="<?= $entity->output("name"); ?>"></td>
	                        <td>
	                        	<select name="" disabled>
	                            	<?php
										$rs_methods = $mysqli->query("SELECT * FROM store_shipping_methods WHERE language_id = " . $language_id . " ORDER BY priority") or die($mysqli->error);
										if($rs_methods->num_rows){
											while($rec_method = $rs_methods->fetch_object()){
												$selected = $rec_method->id == $entity->getDBValue('shipping_method_id') ? ' selected' : '';
									?>
	                                <option value="<?= $rec_method->id ?>"<?= $selected ?>><?= $rec_method->name ?></option>
	                                <?php
											}
										}
	                                ?>
	                            </select>
	                        </td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	            	<?php
						$result_rel_countries = $mysqli->query("SELECT * FROM geo_countries ORDER BY name") or die($mysqli->error);
						if($result_rel_countries->num_rows){
					?>
	                <ul class="related">
	                	<?php
							while($country = $result_rel_countries->fetch_object()){
								$id = $country->$pk;

								// check if record is associated
								$rs_checked = $mysqli->query("SELECT * FROM store_shipping_countries WHERE zone_id = " . $entity->getDBValue($pk) . " AND shipping_method_id = " . $entity->getDBValue('shipping_method_id') . " AND country_id = " . $id . ";") or die($mysqli->error);

								$checked = "";
								if($rs_checked->num_rows || isset($_POST['country_id'][$id])){
									$checked = " checked";
								}

								// check if method is associated to a different zone for the same shipping method
								$rs_disabled = $mysqli->query("SELECT t2.name FROM store_shipping_countries t1, store_shipping_zones t2 WHERE t1.zone_id = t2.id AND t1.shipping_method_id = t2.shipping_method_id AND t1.zone_id != " . $entity->getDBValue($pk) . " AND t1.shipping_method_id = " . $entity->getDBValue('shipping_method_id') . " AND t1.country_id = " . $id . ";") or die($mysqli->error);

								$disabled = "";
								$zone = "";
								if($rs_disabled->num_rows){
									$line_disabled = $rs_disabled->fetch_object();
									$disabled = " disabled";
									$zone = " (" . $line_disabled->name . ")";
								}
						?>
	                	<li><input type="checkbox" name="country_id[<?= $id; ?>]" id="country_id[<?= $id; ?>]" value="<?= $id; ?>"<?= $checked; ?><?= $disabled; ?>> <label for="country_id[<?= $id; ?>]"><?= $country->name . $zone; ?></label></li>
	                	<?php
							}
						?>
	                </ul>

	                <hr class="clear">
	                <?php
						}
					?>
	            </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="update">
	        </form>
	  	</div>

		<?php $template->importScripts(); ?>
	</body>
</html>
