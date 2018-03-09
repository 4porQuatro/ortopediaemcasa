<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "email_messages";
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
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// if the user is not super admin, then we must fetch the current ref from database
			if(!$_SESSION['sgc_super_user']){
				$posts['ref'] = $entity->getDBValue('ref');
			}

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET subject = ?, message = ?, ref = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing to update record...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sss",
				$posts['subject'],
				$posts['message'],
				$posts['ref']
			);
			$stmt_update->execute() or die('<h3>Updating record...</h3>' . $stmt_update->error);

			/*......................................................................................*/

			/*
			*	delete related receivers
			*/
			$mysqli->query("DELETE FROM email_message_email_receiver WHERE email_message_id = " . $entity->getDBValue($pk));

			// insert related receivers
			if(isset($_POST['email_receiver_id'])){
				$stmt_insert_rel_receiver = $mysqli->prepare("INSERT INTO email_message_email_receiver  (email_message_id, email_receiver_id) VALUES (" . $entity->getDBValue($pk) . ", ?); ") or die($mysqli->error);
				$stmt_insert_rel_receiver->bind_param("i", $id);

				foreach($_POST['email_receiver_id'] as $id){
					if(isset($_POST['email_receiver_id'][$id])){
						$stmt_insert_rel_receiver->execute() or die($stmt_insert_rel_receiver->error);
					}
				}
			}

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
	            <li>Destinatários</li>
	        </ul>
	        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	        	<div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Assunto*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="subject" maxlength="<?= $entity->maxlen("subject") ?>" value="<?= $entity->output("subject") ?>"></td>
	                    </tr>
	                    <tr>
	                        <th>Mensagem*</th>
	                    </tr>
	                    <tr>
	                        <td><textarea name="message"><?= $entity->output("message") ?></textarea></td>
	                    </tr>
	                </table>


		        	<?php if($_SESSION['sgc_super_user']){ ?>
					<table>
						<tr>
							<th style="width:30%">Referência*</th>
							<th>&nbsp;</th>
						</tr>
						<tr>
	                        <td><input type="text" name="ref" maxlength="<?= $entity->maxlen("ref") ?>" value="<?= $entity->output("ref") ?>"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
		            <?php } ?>
	            </div>

	        	<div class="form_pane">
	            	<?php
						$result_rel_receivers = $mysqli->query("SELECT " . $pk . ", name, email FROM email_receivers ORDER BY email ASC") or die($mysqli->error);
						if($result_rel_receivers->num_rows){
					?>
	                <ul class="related">
	                	<?php
							while($rel_receiver = $result_rel_receivers->fetch_object()){
								$id = $rel_receiver->$pk;

								// check if record is associated
								$result = $mysqli->query("SELECT email_message_id FROM email_message_email_receiver WHERE email_message_id = " . $entity->getDBValue($pk) . " AND email_receiver_id = " . $id) or die($mysqli->error);

								$checked = "";
								if($result->num_rows || isset($_POST['email_receiver_id'][$id])){
									$checked = " checked";
								}
						?>
	                	<li><input type="checkbox" name="email_receiver_id[<?= $id; ?>]" id="email_receiver_id_<?= $id; ?>" value="<?= $id; ?>"<?= $checked; ?>> <label for="email_receiver_id_<?= $id; ?>"><?= $rel_receiver->email; ?> <?php if(!empty($rel_receiver->name)) echo ' | ' . $rel_receiver->name; ?></label></li>
	                	<?php
							}
						?>
	                </ul>

	                <hr class="clear">
	                <?php
						}
					?>
	            </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="update">
	        </form>
	  	</div>
		<?php $template->importScripts(); ?>
	    <script src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script>
	    $(function(){
	        CKEDITOR.replaceAll(function( textarea, config ){});
	    });
	    </script>
	</body>
</html>
