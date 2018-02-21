<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "store_vouchers";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}
		if(!empty($posts['cost']) && !validate()->isFloat($posts['cost'])){
			$errors .= "<br>O custo deve ser um valor decimal.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (title, description, code, value, percentage, category_id, expires_at, active) VALUES(?, ?, ?, ?, ?, ?, ?, ?)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sssiiisi",
				$posts['title'],
				$posts['description'],
				$posts['code'],
				$posts['value'],
				$posts['percentage'],
				$posts['category_id'],
				$posts['expires_at'],
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
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
                        <th colspan="4">Nome*</th>
                    </tr>
                    <tr>
                        <td colspan="4"><input type="text" name="title" maxlength="<?= $entity->maxlen("title"); ?>" value="<?= $entity->output("title"); ?>"></td>
                    </tr>

                    <tr>
                        <th colspan="4">Descrição*</th>
                    </tr>
                    <tr>
                        <td colspan="4"><input type="text" name="description" maxlength="<?= $entity->maxlen("description"); ?>" value="<?= $entity->output("description"); ?>"></td>
                    </tr>

                    <tr>
                        <th style="width:25%">Código*</th>
                        <th style="width:25%">Data de expiração*</th>
                        <th style="width:25%">Valor*</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="code" maxlength="<?= $entity->maxlen("code"); ?>" value="<?= $entity->output("code"); ?>"></td>
                        <td><input type="text" name="expires_at" id="expires_at" maxlength="10" value="<?= $entity->output("expires_at"); ?>"></td>
                        <td><input type="text" name="value" maxlength="<?= $entity->maxlen("value"); ?>" value="<?= $entity->output("value"); ?>"></td>
                        <td><input type="checkbox" name="percentage" id="percentage" value="1"<?php if($entity->getScopeValue("percentage") == 1) echo ' checked'; ?>> <label for="percentage">Em percentagem</label></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th style="width:50%">Categoria *</th>
                    </tr>
                    <tr>
                        <td>
                        	<select name="category_id">
                            	<option value="">Selecione...</option>
                                <?php
									$rs_types = $mysqli->query("SELECT id, title FROm items_types WHERE language_id = " . $language_id . " ORDER BY priority ASC") or die($mysqli->error);
									if($rs_types->num_rows){
										while($rec_type = $rs_types->fetch_object()){
								?>
								<optgroup label="<?= $rec_type->title ?>">
									<?php
                                        $result = $mysqli->query("SELECT * FROM items_categories WHERE language_id = " . $language_id . " AND type_id = " . $rec_type->id . " ORDER BY priority ASC") or die($mysqli->error);
                                        while($rec = $result->fetch_object()){
                                            $selected = ($rec->id == $entity->getScopeValue("category_id")) ? ' selected' : '';
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
            <input type="hidden" name="op" value="insert">
        </form>
	</div>

	<?php $template->importScripts(); ?>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
		$('#expires_at').datepicker({dateFormat: 'yy-mm-dd'})
	});
	</script>
</body>
</html>
