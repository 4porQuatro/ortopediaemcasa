<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "page_articles";
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
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET title = ?, subtitle = ?, content = ?, images = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"ssss",
				$posts['title'],
				$posts['subtitle'],
				$posts['content'],
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

			<?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram detetados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Imagens</li>
	        </ul>
	        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Título *</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
	                    </tr>
	                    <tr>
	                        <th>Sub-Título</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="subtitle" maxlength="<?= $entity->maxlen("subtitle") ?>" value="<?= $entity->output("subtitle") ?>"></td>
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

	            <input type="submit" value="Save">
	            <input type="hidden" name="op" value="update">
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
