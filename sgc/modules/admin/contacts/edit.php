<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "app_contacts";
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
		if(!empty($posts['email']) && !validate()->isEmail($posts['email'])){
			$errors .= "<br>E-mail inválido.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET name = ?, address_1st_line = ?, address_2nd_line = ?, zip_code = ?, city = ?, country = ?, latitude = ?, longitude = ?, directions = ?, phone = ?, cell_phone = ?, fax = ?, email = ?, working_hours = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk)) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"ssssssssssssssi",
				$posts['name'],
				$posts['address_1st_line'],
				$posts['address_2nd_line'],
				$posts['zip_code'],
				$posts['city'],
				$posts['country'],
				$posts['latitude'],
				$posts['longitude'],
				$posts['directions'],
				$posts['phone'],
				$posts['cell_phone'],
				$posts['fax'],
				$posts['email'],
				$posts['working_hours'],
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
	                        <th colspan="3">Nome ou empresa*</th>
	                    </tr>
	                    <tr>
	                        <td colspan="3"><input type="text" name="name" maxlength="80" value="<?= $entity->output("name"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th colspan="3">Morada (1ª linha)</th>
	                    </tr>
	                    <tr>
	                        <td colspan="3"><input type="text" name="address_1st_line" maxlength="150" value="<?= $entity->output("address_1st_line"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th colspan="3">Morada (2ª linha)</th>
	                    </tr>
	                    <tr>
	                        <td colspan="3"><input type="text" name="address_2nd_line" maxlength="150" value="<?= $entity->output("address_2nd_line"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th style="width:25%">Código postal</th>
	                        <th colspan="2">Localidade</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="zip_code" maxlength="20" value="<?= $entity->output("zip_code"); ?>"></td>
	                        <td colspan="2"><input type="text" name="city" maxlength="50" value="<?= $entity->output("city"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th style="width:25%">País</th>
	                        <th>Latitude</th>
	                        <th>Longitude</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="country" maxlength="50" value="<?= $entity->output("country"); ?>"></td>
	                        <td><input type="text" name="latitude" maxlength="10" value="<?= $entity->output("latitude"); ?>"></td>
	                        <td><input type="text" name="longitude" maxlength="10" value="<?= $entity->output("longitude"); ?>"></td>
	                    </tr>

	                    <tr>
	                        <th colspan="3">Direções</th>
	                    </tr>
	                    <tr>
	                        <td colspan="3"><input type="text" name="directions" maxlength="300" value="<?= $entity->output("directions"); ?>"></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <th style="width:20%">Telefone</th>
	                        <th style="width:20%">Telemóvel</th>
	                        <th style="width:20%">Fax</th>
	                        <th>E-mail</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="phone" maxlength="20" value="<?= $entity->output("phone"); ?>"></td>
	                        <td><input type="text" name="cell_phone" maxlength="20" value="<?= $entity->output("cell_phone"); ?>"></td>
	                        <td><input type="text" name="fax" maxlength="20" value="<?= $entity->output("fax"); ?>"></td>
	                        <td><input type="text" name="email" maxlength="250" value="<?= $entity->output("email"); ?>"></td>
	                    </tr>

	                    <tr>
	                        <th colspan="4">Horário</th>
	                    </tr>
	                    <tr>
	                        <td colspan="4"><input type="text" name="working_hours" maxlength="250" value="<?= $entity->output("working_hours"); ?>"></td>
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
