<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "page_articles";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Fill in all required fields.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, title, page_id, subtitle, content, images, created_at) VALUES(" . $language_id . ", ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sisss",
				$posts['title'],
				$posts['page_id'],
				$posts['subtitle'],
				$posts['content'],
				$posts['images']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

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
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
		<?php $template->importHeadScripts(); ?>
	</head>

	<body>
		<?php $template->printSideBar($mysqli); ?>

	    <div id="data_container">
	    	<div class="record_options_pane">
	    		<a class="record_opt_btn" href="index.php">&larr; Cancelar</a>
	        </div>

	    	<h2>Criar registo</h2>

	        <?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram detetados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Imagens</li>
	        </ul>
	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	        	<div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Título *</th>
	                        <th style="width:40%">Página *</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
	                        <td>
	                        	<select name="page_id">
	                            	<option value="">Selecione...</option>
	                            	<?php
										$result = $mysqli->query("SELECT * FROM pages WHERE language_id = " . $language_id . " ORDER BY reference") or die($mysqli->error);
										while($rec = $result->fetch_object()){
											$selected = ($rec->id == $entity->getScopeValue("page_id")) ? ' selected' : '';
									?>
									<option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->reference ?></option>
									<?php
										}
									?>
	                            </select>
	                        </td>
	                    </tr>

						<tr>
							<th colspan="2">Sub-título</th>
						</tr>
						<tr>
							<td colspan="2"><input type="text" name="subtitle" maxlength="<?= $entity->maxlen("subtitle") ?>" value="<?= $entity->output("subtitle") ?>"></td>
						</tr>
	                </table>

	                <table>
	                    <tr>
	                        <th>Conteúdo</th>
	                    </tr>
	                    <tr>
	                        <td><textarea name="content"><?= $entity->output("content") ?></textarea></td>
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
	    <script src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
	    <script>
	        CKEDITOR.replaceAll(function(textarea, config){});

			$('[name="images"]').imagesUploader({
				subfolder: '<?= $table ?>',
			});
	    </script>
	</body>
</html>
