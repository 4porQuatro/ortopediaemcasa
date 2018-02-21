<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "item_testimonials";

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
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (language_id, comment, item_id, user_id, active, created_at) VALUES(" . $language_id . ", ?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"siii",
				$posts['comment'],
				$posts['item_id'],
				$posts['user_id'],
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
                        <th style="width:50%">Produto*</th>
                        <th>Utilizador*</th>
                    </tr>
                    <tr>
                        <td>
                        	<select name="item_id">
                            	<option value="">Selecione...</option>
                            	<?php
									$result = $mysqli->query("SELECT id, title FROM items WHERE language_id = " . $language_id . " ORDER BY priority") or die($mysqli->error);
									while($rec = $result->fetch_object()){
										$selected = ($rec->id == $entity->getScopeValue("item_id")) ? ' selected' : '';
								?>
								<option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
								<?php
									}
								?>
                            </select>
                        </td>
                        <td>
                        	<select name="user_id">
                            	<option value="">Selecione...</option>
                            	<?php
									$result = $mysqli->query("SELECT id, billing_name FROM users ORDER BY billing_name") or die($mysqli->error);
									while($rec = $result->fetch_object()){
										$selected = ($rec->id == $entity->getScopeValue("user_id")) ? ' selected' : '';
								?>
								<option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->billing_name ?></option>
								<?php
									}
								?>
                            </select>
                        </td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th>Comentário*</th>
                    </tr>
                    <tr>
                        <td><textarea name="comment"><?= $entity->output("comment") ?></textarea></td>
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
    $(function(){
		CKEDITOR.replaceAll(function(textarea, config){});
    });
    </script>
</body>
</html>
