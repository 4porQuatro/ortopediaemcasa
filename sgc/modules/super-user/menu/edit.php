<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "sgc_menu";
	$pk = "menu_id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash']);

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
			$errors .= "<br>Preencha os campos de preenchimento obrigatório.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// update priorities
			$priority = $entity->getDBValue('priority');
			if(intval($posts['parent_id']) != intval($entity->getDBValue('parent_id'))){
				$priority = 1;

				// decrease priorities for previous menu
				$parent_id_clause = ($entity->getDBValue('parent_id')) ? "= " . $entity->getDBValue('parent_id') : "IS NULL";

				$mysqli->query("UPDATE $table set priority = (priority - 1) WHERE parent_id " . $parent_id_clause . " AND priority > " . $entity->getDBValue('priority'));

				// increase priorities for new menu
				$parent_id_clause = ($posts['parent_id']) ? "= " . $posts['parent_id'] : "IS NULL";

				$mysqli->query("UPDATE $table set priority = (priority + 1) WHERE parent_id " . $parent_id_clause);
			}

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET priority = ?, title = ?, parent_id = ?, folder = ?, super_user = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk)) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"isisii",
				$priority,
				$posts['title'],
				$posts['parent_id'],
				$posts['folder'],
				$posts['super_user'],
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
<?php $template->importHeadScripts(); ?>
</head>
<body>
	<?php $template->printSideBar($mysqli); ?>

    <div id="data_container">
    	<div class="record_options_pane">
    		<a class="record_opt_btn" href="index.php">&larr; Cancelar</a>
        </div>

    	<h2>Editar registo #<?= $entity->getDBValue($pk); ?></h2>

		<?php
			if(isset($errors) && !empty($errors))
				echo '<p class="error"><b>Foram detetados os seguintes erros:</b>' . $errors . '</p>';
		?>

        <ul id="form_menu">
            <li>Geral</li>
        </ul>
        <form class="form_model" name="edit_menu_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
            <div class="form_pane">
                <table>
                    <tr>
                        <th>Menu*</th>
                        <th style="width:200px;">Menu pai</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title" maxlength="30" value="<?= $entity->output("title"); ?>"></td>
                        <td>
                            <select name="parent_id">
                            	<option value="">Selecione...</option>
                            	<?php
									$rs_menus = $mysqli->query("SELECT * FROM " . $table . " WHERE parent_id IS NULL ORDER BY priority ASC") or die($mysqli->error);
									if($rs_menus->num_rows){
										while($menu = $rs_menus->fetch_object()){
											$selected = ($menu->menu_id == $entity->getScopeValue("parent_id")) ? ' selected' : '';
								?>
								<option value="<?= $menu->menu_id; ?>"<?= $selected; ?>><?= $menu->title; ?></option>
								<?php
											$rs_submenus = $mysqli->query("SELECT * FROM " . $table . " WHERE parent_id = " . $menu->menu_id . " ORDER BY priority ASC") or die($mysqli->error);
											if($rs_submenus->num_rows){
												while($submenu = $rs_submenus->fetch_object()){
													$selected = ($submenu->menu_id == $entity->getScopeValue("parent_id")) ? ' selected' : '';
								?>
								<option value="<?= $submenu->menu_id; ?>"<?= $selected; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?= $submenu->title; ?></option>
								<?php
												}
											}
										}
									}
								?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2">Pasta*</th>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="text" name="folder" maxlength="30" value="<?= $entity->output("folder"); ?>"></td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td style="width: 20%;">
							<input type="checkbox" name="super_user" id="super_user" value="1"<?php if($entity->getScopeValue("super_user") == 1) echo ' checked'; ?>>
							<label for="super_user">Super user</label>
						</td>
                    </tr>
                    <tr>
                        <td>
							<input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>>
							<label for="active">Publicar</label>
						</td>
                    </tr>
                </table>
            </div>

            <input type="submit" value="Guardar">
            <input type="hidden" name="op" value="update">
        </form>
  	</div>

	<?php $template->importScripts(); ?>
</body>
</html>
