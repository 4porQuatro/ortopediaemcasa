<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "sgc_menu";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
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
			$parent_id_clause = (empty($posts['parent_id'])) ? "IS NULL" : "= " . $posts['parent_id'];
			$mysqli->query("UPDATE $table SET priority = priority + 1 WHERE parent_id $parent_id_clause") or die('<h3>Updating priorities...</h3>' . $mysqli->error);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (title, parent_id, folder, super_user, active) VALUES(?, ?, ?, ?, ?)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sisii",
				$posts['title'],
				$posts['parent_id'],
				$posts['folder'],
				$posts['super_user'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

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

    	<h2>Novo registo</h2>

        <?php
			if(isset($errors) && !empty($errors))
				echo '<p class="error"><b>Foram detetados os seguintes erros:</b>' . $errors . '</p>';
        ?>

        <ul id="form_menu">
            <li>Geral</li>
        </ul>
        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
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
            <input type="hidden" name="op" value="insert">
        </form>
	</div>

    <?php $template->importScripts(); ?>
</body>
</html>
