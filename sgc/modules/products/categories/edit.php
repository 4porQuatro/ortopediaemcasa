<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "items_categories";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash'], $language_id);

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

			// set url rewrite
			$slug = ($posts['title'] != $entity->getDBValue("title")) ? createSlug($posts['title'], $table, $mysqli) : $entity->getDBValue("slug");

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET title = ?, slug = ?, type_id = ?, description = ?, keywords = ?, highlight = ?, active = ?, images = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
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
			$stmt_update->execute() or die('<h3>Updating record...</h3>' . $stmt_update->error);

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
            <li>SEO</li>
			<li>Imagens</li>
        </ul>

        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
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
            <input type="hidden" name="op" value="update">
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