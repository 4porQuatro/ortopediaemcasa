<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "store_taxes";
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

			// update priorities
			$mysqli->query("UPDATE " . $table . " SET priority = priority + 1") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, title, percentage, active) VALUES(" . $language_id . ", ?, ?, ?)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sii",
				$posts['title'],
				$posts['percentage'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Executing statement...</h3>' . $stmt_insert->error);
			$stmt_insert->store_result();

			$fk_id = $mysqli->insert_id;

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
            <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
                <div class="form_pane">
					<table>
                        <tr>
                            <th>Nome *</th>
							<th style="width:15%;">Taxa (%)</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="title" maxlength="60" value="<?= $entity->output("title"); ?>"></td>
							<td><input type="text" name="percentage" maxlength="3" value="<?= $entity->output("percentage"); ?>"></td>
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
