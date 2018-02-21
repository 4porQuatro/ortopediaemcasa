<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "geo_countries";

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
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (name, iso, active, created_at) VALUES(?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"ssi",
				$posts['name'],
				$posts['iso'],
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
                        <th>
                            ISO *
                            (<i><a href="http://kirste.userpage.fu-berlin.de/diverse/doc/ISO_3166.html" target="_blank">Conulte aqui a convenção internacional antes de alterar o código ISO do país</a></i>)
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="iso" maxlength="<?= $entity->maxlen("iso") ?>" value="<?= $entity->output("iso") ?>">
                        </td>
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
</body>
</html>
