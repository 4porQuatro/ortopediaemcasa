<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "languages";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha os campos de preenchimento obrigatório.";
		}

		if(!empty($posts['iso']) && !$entity->checkUniqueKey('iso')){
			$errors .= "<br>O ISO indicada já foi atribuído a outro registo.";
		}
		if(!empty($posts['locale']) && !$entity->checkUniqueKey('locale')){
			$errors .= "<br>O locale indicada já foi atribuído a outro registo.";
		}
		if(!empty($posts['slug']) && !$entity->checkUniqueKey('slug')){
			$errors .= "<br>A slug indicada já foi atribuída a outro registo.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language, iso, locale, slug, active, created_at) VALUES(?, ?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"ssssi",
				$posts['language'],
				$posts['iso'],
				$posts['locale'],
				$posts['slug'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

			/*......................................................................................*/

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
	        </ul>
	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	        	<div class="form_pane">
					<table>
	                    <tr>
	                        <th colspan="4">Title *</th>
	                    </tr>
	                    <tr>
	                        <td colspan="4"><input type="text" name="language" maxlength="<?= $entity->maxlen("language") ?>" value="<?= $entity->output("language") ?>"></td>
	                    </tr>

	                    <tr>
	                        <th style="width:20%;">ISO *</th>
	                        <th style="width:20%;">Locale *</th>
	                        <th style="width:20%;">Slug</th>
							<th>&nbsp;</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="iso" maxlength="<?= $entity->maxlen("iso") ?>" value="<?= $entity->output("iso") ?>"></td>
	                        <td><input type="text" name="locale" maxlength="<?= $entity->maxlen("locale") ?>" value="<?= $entity->output("locale") ?>"></td>
	                        <td><input type="text" name="slug" maxlength="<?= $entity->maxlen("slug") ?>" value="<?= $entity->output("slug") ?>"></td>
							<td>&nbsp;</td>
	                    </tr>
	                </table>

                    <table>
                        <tr>
                            <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Publicar</label></td>
                        </tr>
                    </table>
	            </div>

	            <input type="submit" value="Guardar">
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>

		<?php $template->importScripts(); ?>
	</body>
</html>
