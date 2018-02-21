<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "store_shipping_methods";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash'], $language_id);

	// get zones
	$zones_arr = array();
	$rs_zones = $mysqli->query("SELECT * FROM store_shipping_zones WHERE shipping_method_id = " . $entity->getDBValue('id')) or die($mysqli->error);
	if($rs_zones->num_rows){
		while($rec_zone = $rs_zones->fetch_object()){
			array_push($zones_arr, $rec_zone);
		}
	}

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
		if(!empty($posts['cost']) && !validate()->isFloat($posts['cost'])){
			$errors .= "<br>O custo deve ser um valor decimal.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ?, description = ?, final_message = ?, cost = ?, tax_id = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sssdii",
				$posts['name'],
				$posts['description'],
				$posts['final_message'],
				$posts['cost'],
				$posts['tax_id'],
				$posts['active']
			);
			$stmt_update->execute() or die('<h3>Updating record...</h3>' . $stmt_update->error);

			/*......................................................................................*/

			/*
			*	Delete limits
			*/
			$mysqli->query("DELETE FROM store_shipping_limits WHERE shipping_method_id = " . $entity->getDBValue($pk)) or die($mysqli->error);

			if(isset($_POST['limit_key'])){
				$stmt_insert_limit = $mysqli->prepare(
					"INSERT INTO store_shipping_limits (shipping_method_id, lower_limit, upper_limit, created_at)
					VALUES(" . $entity->getDBValue($pk) . ", ?, ?, CURRENT_TIMESTAMP)
					ON DUPLICATE KEY UPDATE lower_limit = ?, upper_limit = ?"
				) or die($mysqli->error);
				$stmt_insert_limit->bind_param("dddd", $lower_limit, $upper_limit, $lower_limit, $upper_limit);

				foreach($_POST['limit_key'] as $key=>$row_index){
					$lower_limit = $_POST['lower_limit'][$key];
					$upper_limit = $_POST['upper_limit'][$key];

					if(!empty($lower_limit) || !empty($upper_limit)){
						$stmt_insert_limit->execute() or die($stmt_insert_limit->error);

						$limit_id = $stmt_insert_limit->insert_id;

						$stmt_insert_limit_price = $mysqli->prepare(
							"INSERT INTO store_shipping_prices (shipping_limit_id, shipping_method_id, zone_id, price, created_at)
							VALUES(" . $limit_id . ", " . $entity->getDBValue($pk) . ", ?, ?, CURRENT_TIMESTAMP)
							ON DUPLICATE KEY UPDATE price = ?"
						) or die($mysqli->error);
						$stmt_insert_limit_price->bind_param("idd", $zone_id, $limit_price, $limit_price);

						foreach($zones_arr as $zone){
							$zone_id = $zone->id;
							$limit_price = $_POST['limit_price'][$zone->id][$key];
							$stmt_insert_limit_price->execute() or die($stmt_insert_limit_price->error);
						}
					}
				}
			}

			/*............................................................................*/

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
	            <li>Limites de peso</li>
	        </ul>

	        <form class="form_model" name="edit_product_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Nome*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="name" maxlength="<?= $entity->maxlen("name") ?>" value="<?= $entity->output("name") ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>Beve descrição</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="description" maxlength="<?= $entity->maxlen("description") ?>" value="<?= $entity->output("description") ?>"></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <th>Mensagem final</th>
	                    </tr>
	                    <tr>
	                        <td><textarea name="final_message"><?= $entity->output("final_message"); ?></textarea></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <th style="width:25%">Custo*</th>
	                        <th style="width:25%">Taxa*</th>
	                        <th>&nbsp;</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="cost" width="5" maxlength="8" value="<?= $entity->output("cost"); ?>"></td>
	                        <td>
	                            <select name="tax_id">
	                                <?php
										$result_taxes = $mysqli->query("SELECT id, title FROM store_taxes WHERE language_id = " . $language_id . " AND active ORDER BY priority ASC;") or die($mysqli->error);

										if($result_taxes->num_rows){
											while($row_taxes = $result_taxes->fetch_object()){
												$selected = ($row_taxes->id == $entity->getScopeValue('tax_id')) ? ' selected' : '';
									?>
	                                <option value="<?= $row_taxes->id; ?>" <?= $selected; ?>><?= $row_taxes->title; ?></option>
	                                <?php
											}
										}
									?>
	                            </select>
	                        </td>
	                        <td>&nbsp;</td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
					<?php
						if(!isset($_POST['limit_key'])){
							//-> get limits for this record
							$result_limits = $mysqli->query("SELECT * FROM store_shipping_limits WHERE shipping_method_id = " . $entity->getDBValue($pk) . " ORDER BY lower_limit ASC") or die($mysqli->error);

							// if records were found, transfer them to $_POST
							if($result_limits->num_rows){
								$index = 0;
								while($limit_rec = $result_limits->fetch_object()){
									$_POST['limit_key'][$index] = $index;
									$_POST['lower_limit'][$index] = $limit_rec->lower_limit;
									$_POST['upper_limit'][$index] = $limit_rec->upper_limit;

									// get prices for current limit
									$rs_limit_prices = $mysqli->query("SELECT * FROM store_shipping_prices WHERE shipping_limit_id = " . $limit_rec->id . " AND shipping_method_id = " . $entity->getDBValue($pk)) or die($mysqli->error);
									if($rs_limit_prices->num_rows){
										while($rec_limit_price = $rs_limit_prices->fetch_object()){
											$_POST['limit_price'][$rec_limit_price->zone_id][$index] = $rec_limit_price->price;
										}
									}

									$index++;
								}
							// if no records were found, create one entry on $_POST, in order for the 1st line be presented
							}else{
								$_POST['limit_key'][0] = 0;
								$_POST['lower_limit'][0] = 0.000;
								$_POST['upper_limit'][0] = 0.000;
							}
						}
					?>
	                <table>
	                	<tr>
	                    	<th style="width:15%">Limite inf.</th>
	                    	<th style="width:15%">Limite sup.</th>
							<?php
								foreach($zones_arr as $zone){
							?>
	                        <th style="width:10%"><?= $zone->name ?></th>
	                        <?php
								}
							?>
	                    	<th colspan="2">&nbsp;</th>
	                    </tr>
	                    <?php
							foreach($_POST['limit_key'] as $key=>$value){
	                    ?>
	                    <tr class="df_row">
	                        <td><input type="text" name="lower_limit[]" value="<?= isset($_POST['lower_limit'][$key]) ? $_POST['lower_limit'][$key] : 0.000; ?>" placeholder="Limite inf." autocomplete="off"></td>
	                        <td><input type="text" name="upper_limit[]" value="<?= isset($_POST['upper_limit'][$key]) ? $_POST['upper_limit'][$key] : 0.000; ?>" placeholder="Limite sup." autocomplete="off"></td>
	                        <?php
								foreach($zones_arr as $zone){
							?>
	                        <td><input type="text" name="limit_price[<?= $zone->id ?>][]" value="<?= isset($_POST['limit_price'][$zone->id][$key]) ? $_POST['limit_price'][$zone->id][$key] : ''; ?>" placeholder="Custo (€)"></td>
	                        <?php
								}
							?>
	                        <td style="width:1px">
	                        	<input type="hidden" name="limit_key[]" value="<?= $key ?>">
	                        	<span class="inline_btn df_remove_row">Remover</span>
	                        </td>
	                        <td>&nbsp;</td>
	                    </tr>
	                    <?php
							}
	                    ?>
	                    <tr>
	                        <td colspan="<?= (sizeof($zones_arr) + 4) ?>"><input type="button" class="inline_btn" id="df_add_btn" value="Adicionar limite"></td>
	                    </tr>
	                </table>
	            </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="update">
	        </form>
	  	</div>

		<?php $template->importScripts(); ?>
	    <script type="text/javascript" src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script type="text/javascript" src="../../../assets/js/dynamicFields.js"></script>
	    <script type="text/javascript">
	    $(function(){
	        CKEDITOR.replaceAll(function( textarea, config ){});
			dynamicFields('#df_add_btn', '.df_remove_row', '.df_row');
	    });
	    </script>
	</body>
</html>
