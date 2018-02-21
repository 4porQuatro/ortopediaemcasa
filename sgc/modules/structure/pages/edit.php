<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "pages";
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
			$errors .= "<br>Fill in all required fields.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET reference = ?, title = ?, description = ?, keywords = ?, images = ? WHERE $pk = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sssss",
				$posts['reference'],
				$posts['title'],
				$posts['description'],
				$posts['keywords'],
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
	    		<a class="record_opt_btn" href="index.php">&larr; Cancel</a>
	        </div>
	    	<h2>Edit record #<?= $entity->getDBValue($pk); ?></h2>

	        <?php if(isset($_GET['notification']) && $_GET['notification'] == "success"){ ?>
	        <p class="success"><b>As notificações foram enviadas com sucesso!</b></p>
	        <?php } ?>

			<?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram detetados os seguintes erros:</b>' . $errors . '</p>';
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
	                        <th>Referência da página*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="reference" maxlength="<?= $entity->maxlen("reference") ?>" value="<?= $entity->output("reference"); ?>"></td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Title*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title"); ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>Description</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="description" maxlength="<?= $entity->maxlen("description") ?>" value="<?= $entity->output("description"); ?>" placeholder="Type a short description for this record."></td>
	                    </tr>

	                    <tr>
	                        <th>Keywords</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="keywords" maxlength="<?= $entity->maxlen("keywords") ?>" value="<?= $entity->output("keywords"); ?>" placeholder="Type keywords related to this record."></td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	            	<h3>Imagens para partilha nas redes sociais</h3>

	            	<input type="hidden" name="images" value="<?= $entity->output("images") ?>">
	            </div>

	            <input type="submit" value="Save">
	            <input type="hidden" name="op" value="update">
	        </form>
	    </div>

		<?php $template->importScripts(); ?>
	    <script type="text/javascript" src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
	    <script type="text/javascript">
	    $(function() {
			$('[name="images"]').imagesUploader({
				subfolder: '<?= $table ?>',
			});
	    });
	    </script>
	</body>
</html>
