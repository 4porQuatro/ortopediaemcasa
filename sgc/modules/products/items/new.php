<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "items";
	$pk = "id";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}
		if(!empty($posts['price']) && !validate()->isFloat($posts['price'])){
			$errors .= "<br>O preço deve ser um valor decimal.";
		}
		if(!empty($posts['promo_price']) && !validate()->isFloat($posts['promo_price'])){
			$errors .= "<br>O preço promocional deve ser um valor decimal.";
		}
		if(!empty($posts['weight']) && !validate()->isFloat($posts['weight'])){
			$errors .= "<br>O peso deve ser um valor decimal.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			$slug = createSlug($posts['title'], $table, $mysqli);

			// insert record
			$stmt_insert = $mysqli->prepare("
				INSERT INTO " . $table . " (language_id, reference, title, slug, content, points, category_id, price, promo_price, weight, tax_id, description, keywords, active, highlight, popular, list_images, detail_images, docs, created_at)
				VALUES(" . $language_id . ", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)"
			) or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"ssssiidddissiiisss",
				$posts['reference'],
				$posts['title'],
				$slug,
				$posts['content'],
				$posts['points'],
				$posts['category_id'],
				$posts['price'],
				$posts['promo_price'],
				$posts['weight'],
				$posts['tax_id'],
				$posts['description'],
				$posts['keywords'],
				$posts['active'],
				$posts['highlight'],
				$posts['popular'],
				$posts['list_images'],
				$posts['detail_images'],
				$posts['docs']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

			/*............................................................................*/

			/*
			*	Stocks
			*/
			if(isset($_POST['stock_key'])){
				$stmt_insert_stock = $mysqli->prepare("INSERT INTO items_stocks (item_id, size_id, color_id, stock) VALUES(" . $fk_id . ", ?, ?, ?) ON DUPLICATE KEY UPDATE stock = ?") or die($mysqli->error);
				$stmt_insert_stock->bind_param("iiii", $size_id, $color_id, $stock, $stock);

				foreach($_POST['stock_key'] as $key=>$row_index){
					$size_id = $_POST['size_id'][$key];
					$color_id = $_POST['color_id'][$key];
					$stock = intval($_POST['stock'][$key]);

					if(!empty($size_id) && !empty($color_id)){
						$stmt_insert_stock->execute() or die($stmt_insert_stock->error);
					}
				}
			}

			/*............................................................................*/

			/*
			 *	Insert related items
			 */
			if(isset($_POST['rel_item_id'])){
				$insert_rel_item_query = "INSERT INTO " . $table . "_related  (item_id, language_id, related_item_id) VALUES (" . $fk_id . ", " . $language_id . ", ?) ON DUPLICATE KEY UPDATE related_item_id = ?";
				$stmt_insert_rel_item = $mysqli->prepare($insert_rel_item_query) or die('<h3>Preparing to insert related item...</h3>' . '<p>' . $insert_rel_item_query . '</p>' . $mysqli->error);
				$stmt_insert_rel_item->bind_param("ii", $rel_item_id, $rel_item_id);

				foreach($_POST['rel_item_id'] as $key=>$row_index){
					$rel_item_id = $_POST['rel_item_id'][$key];

					if(!empty($rel_item_id)){
						$stmt_insert_rel_item->execute() or die('<h3>Inserting related item...</h3>' . '<p>' . $insert_rel_item_query . '</p>' . $stmt_insert_rel_item->error);
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
            <li>SEO</li>
            <li>Stock</li>
            <li>Relacionados</li>
            <li>Imagens</li>
            <li>Documentos</li>
        </ul>

        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
        	<div class="form_pane">
				<table>
                    <tr>
                        <th colspan="3">Título *</th>
                        <th style="width:20%;">Pontos *</th>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
                        <td><input type="text" name="points" value="<?= $entity->output("points") ?>"></td>
                    </tr>

                    <tr>
                        <th style="width:20%;">Referência *</th>
                        <th style="width:20%;">Categoria *</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="reference" maxlength="<?= $entity->maxlen("reference") ?>" value="<?= $entity->output("reference") ?>"></td>
                        <td>
                        	<select name="category_id">
                            	<option value="">Selecione...</option>
                                <?php
									$rs_types = $mysqli->query("SELECT id, title FROm items_types WHERE language_id = " . $language_id . " ORDER BY priority ASC") or die($mysqli->error);
									if($rs_types->num_rows){
										while($rec_type = $rs_types->fetch_object()){
								?>
								<optgroup label="<?= $rec_type->title ?>">
									<?php
                                        $result = $mysqli->query("SELECT * FROM items_categories WHERE language_id = " . $language_id . " AND type_id = " . $rec_type->id . " ORDER BY priority ASC") or die($mysqli->error);
                                        while($rec = $result->fetch_object()){
                                            $selected = ($rec->id == $entity->getScopeValue("category_id")) ? ' selected' : '';
                                    ?>
                                    <option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
                                    <?php
                                        }
                                    ?>
                                </optgroup>
                                <?php
										}
									}
								?>
                            </select>
                        </td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th style="width:20%">Peso (kg) *</th>
                        <th style="width:20%">Preço (c/IVA) *</th>
                        <th style="width:20%">Preço promocional (c/IVA)</th>
                        <th>Taxa *</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="weight" maxlength="8" placeholder="Ex: 0.400, 1.250" value="<?= $entity->output("weight") ?>"></td>
                        <td><input type="text" name="price" maxlength="8" placeholder="Ex: 19.99" value="<?= $entity->output("price") ?>"></td>
                        <td><input type="text" name="promo_price" maxlength="8" placeholder="Ex: 19.99" value="<?= $entity->output("promo_price") ?>"></td>
                        <td>
                            <select name="tax_id">
                            	<option value="">Selecione...</option>
                                <?php
									$result = $mysqli->query("SELECT id, title FROM store_taxes WHERE language_id = " . $language_id . " AND active ORDER BY priority ASC") or die($mysqli->error);

									if($result->num_rows){
										while($rec = $result->fetch_object()){
											$selected = ($rec->id == $entity->getScopeValue("tax_id")) ? ' selected' : '';
								?>
                                <option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
                                <?php
										}
									}
								?>
                            </select>
                        </td>
                    </tr>
                </table>

				<table>
                    <tr>
                        <th>Informações</th>
                    </tr>
                    <tr>
                        <td><textarea name="content"><?= $entity->output("content") ?></textarea></td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td style="width:180px;">
                        	<input type="checkbox" name="active" id="active" value="1" <?= ($entity->getScopeValue("active") == 1) ? 'checked' : '' ?>>
                            <label for="active">Ativar</label>
                        </td>
                        <td style="width:180px;">
                        	<input type="checkbox" name="highlight" id="highlight" value="1" <?= ($entity->getScopeValue("highlight") == 1) ? 'checked' : '' ?>>
                        	<label for="highlight">Destacar</label>
                        </td>
                        <td>
                        	<input type="checkbox" name="popular" id="popular" value="1" <?= ($entity->getScopeValue("popular") == 1) ? 'checked' : '' ?>>
                        	<label for="popular">Popular</label>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
                <table>
                    <tr>
                        <th>Descrição</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="description" value="<?= $entity->output("description"); ?>" placeholder="Insira uma breve descrição do registo." maxlength="180"></td>
                    </tr>

                    <tr>
                        <th>Palavras-chave</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="keywords" value="<?= $entity->output("keywords"); ?>" placeholder="Insira palavras-chave relacionadas com o registo (ex: kw1,kw2,kw3)." maxlength="180"></td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
				<?php
					if(!isset($_POST['stock_key'])){
						$_POST['stock_key'][0] = 0;
						$_POST['size_id'][0] = '';
						$_POST['color_id'][0] = '';
						$_POST['stock'][0] = '';
					}
				?>
                <table>
                    <?php
						foreach($_POST['stock_key'] as $key=>$value){
                    ?>
                    <tr class="df_row">
                        <td style="width:35%">
                        	<select name="size_id[]">
                            	<option value="">Tamanhos</option>
								<?php
                                    $result = $mysqli->query("SELECT id, title FROM items_sizes WHERE language_id = " . $language_id);
                                    if($result->num_rows){
                                        while($rec = $result->fetch_object()){
											$selected = (isset($_POST['size_id'][$key]) && $_POST['size_id'][$key] == $rec->id) ? ' selected' : '';
                                ?>
                                <option value="<?= $rec->id; ?>"<?= $selected; ?>><?= $rec->title; ?></option>
                                <?php
										}
									}
								?>
                            </select>
                        </td>
                        <td style="width:35%">
                        	<select name="color_id[]">
                            	<option value="">Cores</option>
								<?php
                                    $result = $mysqli->query("SELECT id, title FROM items_colors WHERE language_id = " . $language_id);
                                    if($result->num_rows){
                                        while($rec = $result->fetch_object()){
											$selected = (isset($_POST['color_id'][$key]) && $_POST['color_id'][$key] == $rec->id) ? ' selected' : '';
                                ?>
                                <option value="<?= $rec->id; ?>"<?= $selected; ?>><?= $rec->title; ?></option>
                                <?php
										}
									}
								?>
                            </select>
                        </td>
                        <td><input type="text" name="stock[]" value="<?= isset($_POST['stock'][$key]) ? $_POST['stock'][$key] : ''; ?>" placeholder="Stock" autocomplete="off"></td>
                        <td style="width:1px">
                        	<input type="hidden" name="stock_key[]" value="<?= $key; ?>">
                        	<span class="inline_btn df_remove_row">Remover</span>
                        </td>
                    </tr>
                    <?php
						}
                    ?>
                    <tr>
                        <td colspan="4">
                            <input type="button" class="inline_btn" id="df_add_btn" value="Adicionar stock">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
				<?php
					if(!isset($_POST['rel_item_key'])){
						$_POST['rel_item_key'][0] = 0;
						$_POST['rel_item_id'][0] = '';
					}
				?>
                <table>
                    <?php
						foreach($_POST['rel_item_key'] as $key=>$value){
                    ?>
                    <tr class="rel_item_row">
                        <td>
                        	<select name="rel_item_id[]">
                            	<option value="">Items</option>
								<?php
                                    $result = $mysqli->query("SELECT id, title FROM items WHERE language_id = " . $language_id);
                                    if($result->num_rows){
                                        while($rec = $result->fetch_object()){
											$selected = (isset($_POST['rel_item_id'][$key]) && $_POST['rel_item_id'][$key] == $rec->id) ? ' selected' : '';
                                ?>
                                <option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
                                <?php
										}
									}
								?>
                            </select>
                        </td>
                        <td style="width:1%">
                        	<input type="hidden" name="rel_item_key[]" value="<?= $key ?>">
                        	<span class="inline_btn rel_item_remove_row">Remover</span>
                        </td>
                    </tr>
                    <?php
						}
                    ?>
                    <tr>
                        <td colspan="4">
                            <input type="button" class="inline_btn" id="rel_item_add_btn" value="Adicionar item">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
				<h3>Imagens para a listagem</h3>
				<input type="hidden" name="list_images" value="<?= $entity->output("list_images") ?>">

                <hr class="hline">

                <h3>Imagens de detalhe</h3>
				<input type="hidden" name="detail_images" value="<?= $entity->output("detail_images") ?>">
            </div>

			<div class="form_pane">
            	<h3>Documentos</h3>
            	<input type="hidden" name="docs" value="<?= $entity->output("docs"); ?>">
            </div>

            <input type="submit" value="Gravar">
            <input type="hidden" name="op" value="insert">
        </form>
	</div>

	<?php $template->importScripts(); ?>
	<script type="text/javascript" src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
    <script type="text/javascript" src="../../../assets/js/dynamicFields.js"></script>
    <script type="text/javascript" src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
	<script type="text/javascript" src="../../../assets/plugins/DocsUploader/doc_uploader.jquery.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
		CKEDITOR.replaceAll(function(textarea, config){});
		$('[name*="images"]').imagesUploader({
			subfolder: '<?= $table ?>',
		});
		$('[name="docs"]').docsUploader();
		dynamicFields('#df_add_btn', '.df_remove_row', '.df_row');
		dynamicFields('#rel_item_add_btn', '.rel_item_remove_row', '.rel_item_row');
    });
    </script>
</body>
</html>
