<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "sgc_users";
	$pk = "user_id";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$entity->addPost('confirm_password');	// add extra field
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields(array('password'))){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}

		if(!empty($posts['email'])){
			if(!validate()->isEmail($posts['email'])){
				$errors .= "<br>E-mail inválido.";
			}
			else if(!$entity->checkUniqueKey('email'))
			{
				$errors .= "<br>O e-mail indicado não se encontra disponível";
			}
		}
		if(!empty($posts['password'])){
			if(empty($_POST['confirm_password'])){
				$errors .= '<br>Confirme a palavra-chave.';
			}else if(!validate()->isSecurePassword($posts['password'])){
				$errors .= "<br>A palavra-chave deve conter no mínimo 8 caracteres.";
			}else if($posts['password'] != $posts['confirm_password']){
				$errors .= "<br>A confirmação da palavra-chave falhou.";
			}
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			$secure_password = new Password();
			$posts['password'] = $secure_password->hashPassword($posts['password']);

			// update priorities
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (name, email, password, active, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"sssi",
				$posts['name'],
				$posts['email'],
				$posts['password'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Inserting record...</h3>' . $stmt_insert->error);

			$fk_id = $mysqli->insert_id;

			/*......................................................................................*/

			// insert new permissions
			$stmt_permissions = $mysqli->prepare("INSERT INTO sgc_permissions(user_id, menu_id) VALUES (" . $fk_id . ", ?)") or die('<h3>Preparign to insert permissions...</h3>' . $mysqli->error);
			$stmt_permissions->bind_param('i', $id);
			foreach($_POST['menu_id'] as $id=>$val){
				if(isset($_POST['menu_id'][$id])){
					$stmt_permissions->execute() or die('<h3>Inserting permissions...</h3>' . $mysqli->error);
				}
			}

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
	            if(isset($errors) && !empty($errors)){
					echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
				}
	        ?>

	        <ul id="form_menu">
	            <li>Geral</li>
	            <li>Permissões</li>
	        </ul>
	        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" autocomplete="off">
	            <div class="form_pane">
	                <table>
	                    <tr>
	                        <th>Nome*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="text" name="name" maxlength="120" value="<?= $entity->output("name"); ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>E-mail*</th>
	                    </tr>
	                    <tr>
	                        <td colspan="2"><input type="text" name="email" maxlength="200"  value="<?= $entity->output("email"); ?>"></td>
	                    </tr>

	                    <tr>
	                        <th>Palavra-chave*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="password" name="password"></td>
	                    </tr>

	                    <tr>
	                        <th>Confirmar palavra-chave*</th>
	                    </tr>
	                    <tr>
	                        <td><input type="password" name="confirm_password"></td>
	                    </tr>
	                </table>

	                <table>
	                    <tr>
	                        <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	            	<?php
						// fetch menus
						$result_menus = $mysqli->query("SELECT * FROM sgc_menu ORDER BY priority ASC;") or die($mysqli->error);
						$total_menus = $result_menus->num_rows;

						if($total_menus){
							$arr = array();
							while($lines_menus = $result_menus->fetch_object()){
								$arr[$lines_menus->parent_id][$lines_menus->menu_id] = $lines_menus;
							}
						}

						if(sizeof($arr)){
							echo
							'<div class="permissions_pane">', PHP_EOL;

							function printMenusPermissions($mysqli, $arr, $parent = NULL){
								echo
								'<ol>', PHP_EOL;

								foreach($arr[$parent] as $id => $val){
									$checked = "";
									if(isset($_POST['menu_id'][$id])){
										$checked = "checked";
									}

									echo
									'<li><input type="checkbox" name="menu_id[' . $id . ']" id="menu_id[' . $id . ']" value="' . $id . '" ' . $checked . '> <label for="menu_id[' . $id . ']">' . $val->title . '</label>', PHP_EOL;

									if(isset($arr[$id])){
										printMenusPermissions($mysqli, $arr, $id);
									}

									echo
									'</li>', PHP_EOL;
								}

								echo
								'</ol>', PHP_EOL;
							}

							printMenusPermissions($mysqli, $arr);

							echo
							'	<hr class="clear">
							</div>', PHP_EOL;
						}
					?>
	            </div>

	            <input type="submit" value="Gravar">
	            <input type="hidden" name="op" value="insert">
	        </form>
		</div>
		<?php $template->importScripts(); ?>
	    <script>
	    $(document).ready(function(){
	        setPermissionsOptions();
	    });
	    </script>
	</body>
</html>
