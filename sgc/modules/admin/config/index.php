<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "app_configs";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, md5(1), $language_id);

	if(!$entity->getDBValue($pk)){
		header("location: ../../../main.php");
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

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ?, owner = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"ss",
				$posts['name'],
				$posts['owner']
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
	    	<h2>Configurações</h2>

			<?php
	            if(isset($_GET['edit']) && $_GET['edit'] == "success"){
					echo '<p class="success"><b>Os dados foram atualizados com sucesso!</b></p>';
	            }

				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Configurações</li>
	        </ul>
	        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Nome*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="name" maxlength="<?= $entity->maxlen("name"); ?>" value="<?= $entity->output("name"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th>Proprietário (copyright)*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="owner" maxlength="<?= $entity->maxlen("owner"); ?>" value="<?= $entity->output("owner"); ?>"></td>
	                    </tr>
	                </table>
	            </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="update">
	        </form>
	  	</div>

		<?php $template->importScripts(); ?>
	</body>
</html>
