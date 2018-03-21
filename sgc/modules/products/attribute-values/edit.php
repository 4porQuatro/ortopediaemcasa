<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "item_attribute_values";
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

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET title = ?, item_attribute_type_id = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sii",
				$posts['title'],
				$posts['item_attribute_type_id'],
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
<link rel="stylesheet" type="text/css" href="../../../assets/plugins/MiniColors/jquery.minicolors.css">
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
                        <th>Título*</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
                    </tr>

                    <tr>
                        <th>Tipo *</th>
                    </tr>
                    <tr>
                        <td>
                            <select name="item_attribute_type_id">
                                <option value="">Selecione...</option>
                                <?php
                                    $rs_categories = $mysqli->query("SELECT * FROM item_categories WHERE language_id = " . $language_id . " AND parent_id IS NULL order by priority") or die($mysqli->error);
                                    if($rs_categories->num_rows)
                                    {
                                        while($category = $rs_categories->fetch_object())
                                        {
                                ?>
                                <optgroup label="<?= $category->title ?>">
                                    <?php
                                        $result = $mysqli->query("SELECT * FROM item_attribute_types WHERE language_id = " . $language_id . " AND item_category_id = " . $category->id . " ORDER BY priority") or die($mysqli->error);
                                        while($rec = $result->fetch_object()) {
                                            $selected = ($rec->id == $entity->getScopeValue("item_attribute_type_id")) ? ' selected' : '';
                                    ?>
                                    <option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
                                    <?php
                                        }
                                    ?>
                                </optgroup>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
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
            <input type="hidden" name="op" value="update">
        </form>
  	</div>

	<?php $template->importScripts(); ?>
</body>
</html>
