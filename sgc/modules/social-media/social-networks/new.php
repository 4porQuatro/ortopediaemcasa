<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!$_SESSION['sgc_super_user']){
		header("locarion: index.php");
		exit;
	}

	$table = "social_networks";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
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

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (name, url, slug, active, created_at) VALUES(?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sssi",
				$posts['name'],
				$posts['url'],
				$posts['slug'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

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
	    	<h2>Inserir registo</h2>

	        <?php
				if(isset($errors) && !empty($errors))
					echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
			?>

	        <ul id="form_menu">
	            <li>Geral</li>
	        </ul>

	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" autocomplete="off">
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
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>

		<?php $template->importScripts(); ?>
	</body>
</html>
