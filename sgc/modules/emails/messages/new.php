<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "email_messages";
	$pk = "id";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, subject, message, ref, created_at) VALUES(" . $language_id . ", ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sss",
				$posts['subject'],
				$posts['message'],
				$posts['ref']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

			/*......................................................................................*/

			// insert related receivers
			if(isset($_POST['receiver_id'])){
				$stmt_insert_rel_receiver = $mysqli->prepare("INSERT INTO email_message_receivers  (message_id, receiver_id) VALUES (" . $fk_id . ", ?); ") or die($mysqli->error);
				$stmt_insert_rel_receiver->bind_param("i", $id);

				foreach($_POST['receiver_id'] as $id){
					if(isset($_POST['receiver_id'][$id])){
						$stmt_insert_rel_receiver->execute() or die($stmt_insert_rel_receiver->error);
					}
				}
			}

			/*.........................................................................*/

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
	    	<h2>Inserir registo</h2>

	        <?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Destinatários</li>
	        </ul>
	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
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
	            </div>

	        	<div class="form_pane">
	            	<?php
						$rs_rel_receivers = $mysqli->query("SELECT " . $pk . ", name, email FROM email_receivers ORDER BY email ASC") or die($mysqli->error);
						if($rs_rel_receivers->num_rows){
					?>
	                <ul class="related">
	                	<?php
	                    	while($rel_receiver = $rs_rel_receivers->fetch_object()){
								$id = $rel_receiver->$pk;

								$checked = (isset($_POST['receiver_id'][$id])) ? " checked" : "";
	                    ?>
	                	<li><input type="checkbox" name="receiver_id[<?= $id; ?>]" id="receiver_id_<?= $id; ?>" value="<?= $id; ?>" <?= $checked; ?>> <label for="receiver_id_<?= $id; ?>"><?= $rel_receiver->email; ?> <?php if(!empty($rel_receiver->name)) echo ' | ' . $rel_receiver->name; ?></label></li>
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
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>
		<?php $template->importScripts(); ?>
	    <script type="text/javascript" src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script type="text/javascript">
	    $(function(){
	        CKEDITOR.replaceAll(function( textarea, config ){});
	    });
	    </script>
	</body>
</html>
