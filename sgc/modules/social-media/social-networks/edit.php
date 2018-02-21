<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "social_networks";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash']);

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

		if(!empty($posts['url']) && !validate()->isURL($posts['url'])){
			$errors .= "<br>URL inválida.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// if the user is not super admin, then we must fetch the current css class from database
			if(!$_SESSION['sgc_super_user']){
				$posts['slug'] = $entity->getDBValue('slug');
			}

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ?, url = ?, slug = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . ";") or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sssi",
				$posts['name'],
				$posts['url'],
				$posts['slug'],
				$posts['active']
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
	        </ul>
	        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th colspan="2">Título*</th>
	                    </tr>
	                    <tr>
	                        <td colspan="2"><input type="text" name="name" maxlength="<?= $entity->maxlen("name") ?>" value="<?= $entity->output("name") ?>"></td>
	                    </tr>

	                    <tr>
	                        <th colspan="2">URL*</th>
	                    </tr>
	                    <tr>
	                        <td colspan="2"><input type="text" name="url" value="<?= $entity->output("url") ?>"></td>
	                    </tr>

	        			<?php if($_SESSION['sgc_super_user']){ ?>
	                    <tr>
	                        <th style="width:25%">Classe CSS</th>
	                        <th>&nbsp;</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="slug" value="<?= $entity->output("slug") ?>"></td>
	                        <td>&nbsp;</td>
	                    </tr>
	                    <?php } ?>
	                </table>

	                <table>
	                    <tr>
	                        <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
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
