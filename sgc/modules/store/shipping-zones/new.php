<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "store_shipping_zones";
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
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (name, shipping_method_id, created_at) VALUES(?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"si",
				$posts['name'],
				$posts['shipping_method_id']
			);
			$stmt_insert->execute() or die('<h3>Updating record...</h3>' . $stmt_insert->error);

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
            <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
                <div class="form_pane">
                    <table>
                        <tr>
                            <th>Nome*</th>
                            <th style="width:25%">Método de envio*</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="name" maxlength="<?= $entity->maxlen("name"); ?>" value="<?= $entity->output("name"); ?>"></td>
                            <td>
                                <select name="shipping_method_id">
                                    <option value="">Selecione...</option>
                                    <?php
                                        $result = $mysqli->query("SELECT * FROM store_shipping_methods WHERE language_id = " . $language_id . " ORDER BY priority") or die($mysqli->error);
                                        while($rec = $result->fetch_object()){
                                            $selected = ($rec->id == $entity->getScopeValue("shipping_method_id")) ? ' selected' : '';
                                    ?>
                                    <option value="<?= $rec->id; ?>"<?= $selected; ?>><?= $rec->name; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </td>
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
