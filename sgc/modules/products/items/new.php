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
				INSERT INTO " . $table . " (language_id, reference, title, slug, content, points, items_category_id, items_brand_id, price, promo_price, weight, tax_id, description, keywords, active, highlight, list_images, detail_images, created_at, updated_at)
				VALUES(" . $language_id . ", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
			) or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"ssssiiiddissiiss",
				$posts['reference'],
				$posts['title'],
				$slug,
				$posts['content'],
				$posts['points'],
				$posts['items_category_id'],
                $posts['items_brand_id'],
				$posts['price'],
				$posts['promo_price'],
				$posts['weight'],
				$posts['tax_id'],
				$posts['description'],
				$posts['keywords'],
				$posts['active'],
				$posts['highlight'],
				$posts['list_images'],
				$posts['detail_images']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

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
            <li>Imagens</li>
        </ul>

        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
        	<div class="form_pane">
                <table>
                    <tr>
                        <th colspan="4">Título *</th>
                    </tr>
                    <tr>
                        <td colspan="4"><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
                    </tr>

                    <tr>
                        <th style="width:20%;">Referência *</th>
                        <th style="width:20%;">Categoria *</th>
                        <th style="width:20%;">Marca *</th>
                        <th style="width:20%;">Pontos *</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="reference" maxlength="<?= $entity->maxlen("reference") ?>" value="<?= $entity->output("reference") ?>"></td>
                        <td>
                            <select name="items_category_id">
                                <option value="">Selecione...</option>
                                <?php
                                $categories_rs = $mysqli->query("SELECT id, parent_id, title FROM items_categories WHERE language_id = " . $language_id . " ORDER BY priority");

                                if($categories_rs->num_rows)
                                {
                                    while($category = $categories_rs->fetch_object()){
                                        $categories_arr[$category->parent_id][$category->id] = $category->title;
                                    }

                                    printTreeOptionsSelection($categories_arr, NULL, 0, $entity->getScopeValue("items_category_id"));
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="items_brand_id">
                                <option value="">Selecione...</option>
                                <?php
                                    $result = $mysqli->query("SELECT * FROM items_brands WHERE language_id = " . $language_id . " ORDER BY title") or die($mysqli->error);
                                    while($rec = $result->fetch_object()){
                                        $selected = ($rec->id == $entity->getScopeValue("items_brand_id")) ? ' selected' : '';
                                ?>
                                    <option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td><input type="text" name="points" value="<?= $entity->output("points") ?>"></td>
                    </tr>

                    <tr>
                        <th>Peso (kg) *</th>
                        <th>Preço (c/IVA)*</th>
                        <th>Preço promocional (c/IVA)*</th>
                        <th>Taxa*</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="weight" maxlength="8" placeholder="Ex: 0.400, 1.250" value="<?= $entity->output("weight") ?>"></td>
                        <td><input type="text" name="price" maxlength="8" placeholder="Ex: 19.99" value="<?= $entity->output("price") ?>"></td>
                        <td><input type="text" name="promo_price" maxlength="8" placeholder="Ex: 19.99" value="<?= $entity->output("promo_price") ?>"></td>
                        <td>
                            <select name="tax_id">
                                <option value="">Selecione...</option>
                                <?php
                                $result = $mysqli->query("SELECT id, title FROM store_taxes WHERE language_id = " . $language_id . " AND active ORDER BY priority ASC;") or die($mysqli->error);

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
                        <th>Conteúdo *</th>
                    </tr>
                    <tr>
                        <td><textarea name="content"><?= $entity->output("content") ?></textarea></td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td>
                            <input type="checkbox" name="highlight" id="highlight" value="1" <?= ($entity->getScopeValue("highlight") == 1) ? 'checked' : '' ?>>
                            <label for="highlight">Destacar</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        	<input type="checkbox" name="active" id="active" value="1" <?= ($entity->getScopeValue("active") == 1) ? 'checked' : '' ?>>
                            <label for="active">Ativar</label>
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
		CKEDITOR.replaceAll(function(textarea, config){});
		$('[name*="images"]').imagesUploader({
			subfolder: '<?= $table ?>',
		});
        dynamicFields('#rel_item_add_btn', '.rel_item_remove_row', '.rel_item_row');
    });
    </script>
</body>
</html>
