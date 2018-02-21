<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "items_categories";

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

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// set url rewrite
			$slug = createSlug($posts['title'], $table, $mysqli);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, title, slug, type_id, description, keywords, highlight, active, images, created_at) VALUES(" . $language_id . ", ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"ssissiis",
				$posts['title'],
				$slug,
				$posts['type_id'],
				$posts['description'],
				$posts['keywords'],
				$posts['highlight'],
				$posts['active'],
				$posts['images']
			);
			$stmt_insert->execute() or die('<h3>Executing statement...</h3>' . $stmt_insert->error);
			$stmt_insert->store_result();

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
                        <th>Título *</th>
                        <th style="width:25%">Tipo *</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
                        <td>
                        	<select name="type_id">
                            	<option value="">Selecione...</option>
                            	<?php
									$result = $mysqli->query("SELECT * FROM items_types WHERE language_id = " . $language_id . " ORDER BY priority ASC;") or die($mysqli->error);
									while($rec = $result->fetch_object()){
										$selected = ($rec->id == $entity->getScopeValue("type_id")) ? ' selected' : '';
								?>
								<option value="<?= $rec->id; ?>"<?= $selected; ?>><?= $rec->title; ?></option>
								<?php
									}
								?>
                            </select>
                        </td>
                    </tr>
                </table>

                <table>
					<tr>
						<td><input type="checkbox" name="highlight" id="highlight" value="1"<?php if($entity->getScopeValue("highlight") == 1) echo ' checked'; ?>> <label for="highlight">Destacar</label></td>
					</tr>
                    <tr>
                        <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
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
				<h3>Imagens</h3>

				<input type="hidden" name="images" value="<?= $entity->output("images") ?>">
			</div>

            <input type="submit" value="Gravar">
            <input type="hidden" name="op" value="insert">
        </form>
	</div>

	<?php $template->importScripts(); ?>
	<script type="text/javascript" src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
	<script type="text/javascript">
	$('[name*="images"], [name="images"]').imagesUploader({
		subfolder: '<?= $table ?>',
	});
	</script>
</body>
</html>
