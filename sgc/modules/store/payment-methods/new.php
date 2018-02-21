<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "store_payment_methods";
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

            $mysqli->query("UPDATE " . $table . " SET priority = priority + 1");

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, name, description, final_message, active) VALUES(" . $language_id . ", ?, ?, ?, ?);") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sssi",
				$posts['name'],
				$posts['description'],
				$posts['final_message'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Executing statement...</h3>' . $stmt_insert->error);
			$stmt_insert->store_result();

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
	        </ul>

	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="form_pane">
					<table>
	                    <tr>
	                        <th>Nome*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="name" maxlength="<?= $entity->maxlen("name") ?>" value="<?= $entity->output("name") ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>Beve descrição</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="description" maxlength="<?= $entity->maxlen("description") ?>" value="<?= $entity->output("description") ?>"></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <th>Mensagem final</th>
	                    </tr>
	                    <tr>
	                        <td><textarea name="final_message"><?= $entity->output("final_message"); ?></textarea></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
	                    </tr>
	                </table>
	            </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>

		<?php $template->importScripts(); ?>

	    <script type="text/javascript" src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script type="text/javascript">
	    $(document).ready(function(){
	        CKEDITOR.replaceAll(function( textarea, config ){});
	    });
	    </script>
	</body>
</html>
