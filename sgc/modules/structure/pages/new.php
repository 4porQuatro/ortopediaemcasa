<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "pages";
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

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, reference, title, description, keywords, images, created_at) VALUES(" . $language_id . ", ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sssss",
				$posts['reference'],
				$posts['title'],
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
	            <li>SEO</li>
	            <li>Imagens</li>
	        </ul>

	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Referência da página *</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="reference" maxlength="<?= $entity->maxlen("reference") ?>" value="<?= $entity->output("reference") ?>"></td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Title *</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>Description</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="description" maxlength="<?= $entity->maxlen("description") ?>" value="<?= $entity->output("description") ?>" placeholder="Type a short description for this record."></td>
	                    </tr>

	                    <tr>
	                        <th>Keywords</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="keywords" maxlength="<?= $entity->maxlen("keywords") ?>" value="<?= $entity->output("keywords") ?>" placeholder="Type keywords related to this record."></td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	            	<h3>Imagens para partilha nas redes sociais</h3>
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
			$('[name="images"]').imagesUploader({
				subfolder: '<?= $table ?>',
			});
	    });
	    </script>
	</body>
</html>
