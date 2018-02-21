<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "newsletter_subscribers";
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
		if(!empty($posts['email'])){
			if(!validate()->isEmail($posts['email']))
				$errors .= "<br>E-mail inválido.";
			else if(!$entity->checkUniqueKey('email')){
				$errors .= "<br>O e-mail indicado não se encontra disponível";
			}
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ?, email = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk)) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"ssi",
				$posts['name'],
				$posts['email'],
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
	        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Nome</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="name" maxlength="250"  value="<?= $entity->output("name"); ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>E-mail*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="email" maxlength="200"  value="<?= $entity->output("email"); ?>"></td>
	                    </tr>
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
