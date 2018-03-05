<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "store_payment_methods";
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
		if(!empty($posts['cost']) && !validate()->isFloat($posts['cost'])){
			$errors .= "<br>O custo deve ser um valor decimal.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ?, description = ?, final_message = ?, active = ?, images = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sssis",
				$posts['name'],
				$posts['description'],
				$posts['final_message'],
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
                <li>Imagens</li>
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
                            <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
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
        <script type="text/javascript" src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
        <script type="text/javascript" src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
        <script type="text/javascript">
        $(function(){
            CKEDITOR.replaceAll(function( textarea, config ){});

            $('[name*="images"], [name="images"]').imagesUploader({
                subfolder: '<?= $table ?>',
            });
        });
        </script>
    </body>
</html>
