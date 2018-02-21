<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "email_configs";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, md5(1));

	if(!$entity->getDBValue($pk)){
		header("location: ../../../../main.php");
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
		if($posts['use_smtp_auth'] && empty($posts['email_password'])){
			$errors .= "<br>Ativou a autenticação SMTP, por isso deve indicar a password associada ao e-mail indicado.";
		}
		if($posts['use_smtp_auth'] && empty($posts['email_host'])){
			$errors .= "<br>Ativou a autenticação SMTP, por isso deve indicar um servidor de envio.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET sender = ?, sender_email = ?, email_password = ?, email_host = ?, use_smtp_auth = ? WHERE " . $pk . " = " . $entity->getDBValue($pk)) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"ssssi",
				$posts['sender'],
				$posts['sender_email'],
				$posts['email_password'],
				$posts['email_host'],
				$posts['use_smtp_auth']
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
	                        <th>Remetente*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="sender" maxlength="<?= $entity->maxlen("sender"); ?>" value="<?= $entity->output("sender"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th>E-mail*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="sender_email" maxlength="<?= $entity->maxlen("sender_email"); ?>" value="<?= $entity->output("sender_email"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th>Password</th>
	                    </tr>
	                    <tr>
	                        <td><input type="password" name="email_password" maxlength="<?= $entity->maxlen("email_password"); ?>" value="<?= $entity->output("email_password"); ?>"></td>
	                    </tr>
	                    <tr>
	                        <th>Servidor de envio</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="email_host" maxlength="<?= $entity->maxlen("email_host"); ?>" value="<?= $entity->output("email_host"); ?>"></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <td><input type="checkbox" name="use_smtp_auth" id="use_smtp_auth" value="1"<?php if($entity->getScopeValue("use_smtp_auth") == 1) echo ' checked'; ?>> <label for="use_smtp_auth">Usar autenticação SMTP</label></td>
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
