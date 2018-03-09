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
			$errors .= "<br>Preencha os campos de preenchimento obrigatório.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update priorities
			$parent_id_clause = (empty($posts['parent_id'])) ? "IS NULL" : "= " . $posts['parent_id'];
			$mysqli->query("UPDATE $table SET priority = priority + 1 WHERE parent_id $parent_id_clause") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (title, parent_id, subtitle, highlight, active, description, keywords, images) VALUES(?, ?, ?, ?, ?, ?, ?, ?)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sisiisss",
				$posts['title'],
				$posts['parent_id'],
				$posts['subtitle'],
                $posts['highlight'],
				$posts['active'],
                $posts['description'],
                $posts['keywords'],
                $posts['images']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

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
            <li>SEO</li>
            <li>Imagens</li>
        </ul>
        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
            <div class="form_pane">
                <table>
                    <tr>
                        <th>Título *</th>
                        <th style="width:200px;">Categoria pai</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
                        <td>
                            <select name="parent_id">
                            	<option value="">Selecione...</option>
                            	<?php
									$rs_menus = $mysqli->query("SELECT * FROM " . $table . " WHERE parent_id IS NULL ORDER BY priority ASC") or die($mysqli->error);
									if($rs_menus->num_rows){
										while($menu = $rs_menus->fetch_object()){
											$selected = ($menu->id == $entity->getScopeValue("parent_id")) ? ' selected' : '';
								?>
								<option value="<?= $menu->id; ?>"<?= $selected; ?>><?= $menu->title; ?></option>
								<?php
											$rs_submenus = $mysqli->query("SELECT * FROM " . $table . " WHERE parent_id = " . $menu->id . " ORDER BY priority ASC") or die($mysqli->error);
											if($rs_submenus->num_rows){
												while($submenu = $rs_submenus->fetch_object()){
													$selected = ($submenu->id == $entity->getScopeValue("parent_id")) ? ' selected' : '';
								?>
								<option value="<?= $submenu->id; ?>"<?= $selected; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?= $submenu->title; ?></option>
								<?php
												}
											}
										}
									}
								?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2">Sub-título *</th>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="text" name="subtitle" maxlength="<?= $entity->maxlen("subtitle") ?>" value="<?= $entity->output("subtitle") ?>"></td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td>
							<input type="checkbox" name="highlight" id="highlight" value="1"<?php if($entity->getScopeValue("highlight") == 1) echo ' checked'; ?>>
							<label for="highlight">Destacar</label>
						</td>
                    </tr>
                    <tr>
                        <td>
							<input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>>
							<label for="active">Publicar</label>
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
                        <td><input type="text" name="description" value="<?= $entity->output("description") ?>" placeholder="Insira uma breve descrição do registo." maxlength="180"></td>
                    </tr>

                    <tr>
                        <th>Palavras-chave</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="keywords" value="<?= $entity->output("keywords") ?>" placeholder="Insira palavras-chave relacionadas com o registo (ex: kw1,kw2,kw3)." maxlength="180"></td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
                <h3>Imagens</h3>
                <input type="hidden" name="images" value="<?= $entity->output("images") ?>">
            </div>

            <input type="submit" value="Guardar">
            <input type="hidden" name="op" value="insert">
        </form>
	</div>

    <?php $template->importScripts(); ?>
    <script src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
    <script>
        $(document).ready(function(){
            $('[name*="images"]').imagesUploader({
                subfolder: '<?= $table ?>',
            });
        })
    </script>
</body>
</html>
