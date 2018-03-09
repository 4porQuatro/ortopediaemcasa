<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "store_shipping_methods";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1");

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, name, description, final_message, cost, tax_id, active, images) VALUES(" . $language_id . ", ?, ?, ?, ?, ?, ?, ?);") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sssdiis",
				$posts['name'],
				$posts['description'],
				$posts['final_message'],
				$posts['cost'],
				$posts['tax_id'],
				$posts['active'],
                $posts['images']
			);
			$stmt_insert->execute() or die('<h3>Executing statement...</h3>' . $stmt_insert->error);
			$stmt_insert->store_result();

			$fk_id = $mysqli->insert_id;

			/*............................................................................*/

			/*
			*	Zones
			*/
			if(isset($_POST['zone_key'])){
				$stmt_insert_stock = $mysqli->prepare("INSERT INTO store_shipping_zones (shipping_method_id, name) VALUES(" . $fk_id . ", ?)") or die($mysqli->error);
				$stmt_insert_stock->bind_param("s", $zone_name);

				foreach($_POST['zone_key'] as $key=>$row_index){
					$zone_name = $_POST['zone_name'][$key];

					if(!empty($zone_name)){
						$stmt_insert_stock->execute() or die($stmt_insert_stock->error);
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

	    	<h2>Inserir registo</h2>

	        <?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Zonas</li>
	            <li>Imagens</li>
	        </ul>

	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
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
	                                <option value="<?= $row_taxes->id ?>" <?= $selected ?>><?= $row_taxes->title ?></option>
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
						if(!isset($_POST['zone_key'])){
							$_POST['zone_key'][0] = 0;
							$_POST['zone_name'][0] = '';
						}
					?>
	                <table>
	                    <?php
							foreach($_POST['zone_key'] as $key=>$value){
	                    ?>
	                    <tr class="df_zone_row">
	                        <td style="width:20%"><input type="text" name="zone_name[]" value="<?= isset($_POST['zone_name'][$key]) ? $_POST['zone_name'][$key] : ''; ?>" placeholder="Nome da Zona"></td>
	                        <td style="width:1px">
	                        	<input type="hidden" name="zone_key[]" value="<?= $key; ?>">
	                        	<span class="inline_btn df_zone_remove_row">Remover</span>
	                        </td>
	                        <td>&nbsp;</td>
	                    </tr>
	                    <?php
							}
	                    ?>
	                    <tr>
	                        <td colspan="3">
	                            <input type="button" class="inline_btn" id="df_zone_add_btn" value="Adicionar zona">
	                        </td>
	                    </tr>
	                </table>
	            </div>

                <div class="form_pane">
                    <h3>Imagens</h3>

                    <input type="hidden" name="images" value="<?= $entity->output("images") ?>">
                </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>

		<?php $template->importScripts(); ?>
	    <script src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script src="../../../assets/js/dynamicFields.js"></script>
        <script src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
	    <script>
	    $(document).ready(function(){
	        CKEDITOR.replaceAll(function( textarea, config ){});

			dynamicFields('#df_zone_add_btn', '.df_zone_remove_row', '.df_zone_row');

            $('[name*="images"], [name="images"]').imagesUploader({
                subfolder: '<?= $table ?>',
            });
	    });
	    </script>
	</body>
</html>
