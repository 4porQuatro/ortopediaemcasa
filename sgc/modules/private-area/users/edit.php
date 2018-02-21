<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

    use App\Lib\Store\Price;

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$table = "users";
	$pk = "id";

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash']);

	if(!$entity->getDBValue($pk)){
		header("location: index.php");
		exit;
	}

	if(isset($_POST['op']) && $_POST['op'] == "update"){
		// map posts
		$entity->addPost('confirm_password');	// add extra field
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		// add exceptions
		$entity->addRequiredException('password');
		$entity->addRequiredException('confirm_password');

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}
		if(!empty($posts['email'])){
			if(!validate()->isEmail($posts['email'])){
				$errors .= "<br>E-mail inválido";
			}else if(!$entity->checkUniqueKey('email')){
				$errors .= "<br>O e-mail indicado não se encontra disponível";
			}
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			$secure_password = new Password();
			$posts['password'] = (empty($posts['password'])) ? $entity->getDBValue('password') : $secure_password->hashPassword($posts['password']);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET billing_name = ?, billing_phone = ?, billing_address = ?, billing_city = ?, billing_zip_code = ?, billing_country_id = ?, shipping_name = ?, shipping_phone = ?, shipping_address = ?, shipping_city = ?, shipping_zip_code = ?, shipping_country_id = ?, vat_number = ?, email = ?, password = ?, active = ? WHERE " . $pk . " = " . $entity->getDBValue($pk)) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"sssssisssssiissii",
				$posts['billing_name'],
				$posts['billing_phone'],
				$posts['billing_address'],
				$posts['billing_city'],
				$posts['billing_zip_code'],
				$posts['billing_country_id'],
				$posts['shipping_name'],
				$posts['shipping_phone'],
				$posts['shipping_address'],
				$posts['shipping_city'],
				$posts['shipping_zip_code'],
				$posts['shipping_country_id'],
				$posts['vat_number'],
				$posts['email'],
				$posts['password'],
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
    	<h2>Editar registo nr.º <?= $entity->getDBValue($pk); ?></h2>

		<?php
			if(isset($errors) && !empty($errors))
				echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
		?>

        <ul id="form_menu">
            <li>Dados de faturação</li>
            <li>Dados de envio</li>
            <li>Conta</li>
            <li>Encomendas</li>
        </ul>

        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
            <div class="form_pane">
                <table>
                    <tr>
                        <th style="width:50%">Nome*</th>
                        <th>NIF</th>
                        <th style="width:25%">Contacto</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="billing_name" maxlength="100" value="<?= $entity->output("billing_name"); ?>"></td>
                        <td><input type="text" name="vat_number" value="<?= $entity->output("vat_number"); ?>"></td>
                        <td><input type="text" name="billing_phone" maxlength="20" value="<?= $entity->output("billing_phone"); ?>"></td>
                    </tr>

                    <tr>
                        <th colspan="3">Morada</th>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="text" name="billing_address" maxlength="120" value="<?= $entity->output("billing_address"); ?>"></td>
                    </tr>

                    <tr>
                        <th>Cidade</th>
                        <th>Código Postal</th>
                        <th>País</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="billing_city" maxlength="60" value="<?= $entity->output("billing_city"); ?>"></td>
                        <td><input type="text" name="billing_zip_code" maxlength="20" value="<?= $entity->output("billing_zip_code"); ?>"></td>
                        <td>
                        	<select name="billing_country_id">
                            	<option value="">Selecione...</option>
								<?php
									$rs_countries = $mysqli->query("SELECT id, name FROM geo_countries ORDER BY name ASC;") or die($mysqli->error);
									if($rs_countries->num_rows){
										while($rec_country = $rs_countries->fetch_object()){
											$selected = ($rec_country->id == $entity->getScopeValue("billing_country_id")) ? ' selected' : '';
								?>
								<option value="<?= $rec_country->id; ?>"<?= $selected; ?>><?= $rec_country->name; ?></option>
								<?php
										}
									}
								?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
                <table>
                    <tr>
                        <th colspan="2">Nome</th>
                        <th style="width:25%">Contacto</th>
                    </tr>
                    <tr>

                        <td colspan="2"><input type="text" name="shipping_name" maxlength="100" value="<?= $entity->output("shipping_name"); ?>"></td>
                        <td colspan="2"><input type="text" name="shipping_phone" maxlength="20" value="<?= $entity->output("shipping_phone"); ?>"></td>

                    </tr>

                    <tr>
                        <th colspan="3">Morada</th>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="text" name="shipping_address" maxlength="120" value="<?= $entity->output("shipping_address"); ?>"></td>
                    </tr>

                    <tr>
                        <th style="width:50%">Cidade</th>
                        <th>Código Postal</th>
                        <th>País</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="shipping_city" maxlength="60" value="<?= $entity->output("shipping_city"); ?>"></td>
                        <td><input type="text" name="shipping_zip_code" maxlength="20" value="<?= $entity->output("shipping_zip_code"); ?>"></td>
                        <td>
                        	<select name="shipping_country_id">
                            	<option value="">Selecione...</option>
								<?php
									$rs_countries = $mysqli->query("SELECT id, name FROM geo_countries ORDER BY name ASC;") or die($mysqli->error);
									if($rs_countries->num_rows){
										while($rec_country = $rs_countries->fetch_object()){
											$selected = ($rec_country->id == $entity->getScopeValue("shipping_country_id")) ? ' selected' : '';
								?>
								<option value="<?= $rec_country->id; ?>"<?= $selected; ?>><?= $rec_country->name; ?></option>
								<?php
										}
									}
								?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="form_pane">
                <table>
                    <tr>
                        <th colspan="2">E-mail*</th>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="text" name="email" maxlength="120"  value="<?= $entity->output("email"); ?>"></td>
                    </tr>

                    <tr>
                        <th>Palavra-chave</th>
                        <th>Confirmar palavra-chave</th>
                    </tr>
                    <tr>
                        <td><input type="password" name="password" maxlength="40"></td>
                        <td><input type="password" name="confirm_password" maxlength="40"></td>
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
                	//get user orders
					$result_orders = $mysqli->query(
					        "SELECT t1.*, t2.title as 'status' FROM store_orders as t1
                                  JOIN store_order_states as t2 on t1.state_id = t2.id
                                  WHERE t1.user_id = " . $entity->getDBValue($pk) . "
                                  ORDER BY created_at DESC"

                    ) or die($mysqli->error);
					$total_orders = $result_orders->num_rows;

					if(!$total_orders){
				?>
                <p class="info">Não exitem encomendas registadas para este utilizador.</p>
                <?php
					}else{
				?>
                <table cellpadding="0" cellspacing="0" width="100%" class="items_list">
                    <tr>
                        <th style="width:50px;text-align:center">Nr.º</th>
                        <th>Data / Hora</th>
                        <th>Método de envio</th>
                        <th>Pontos</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                  	<?php
                        $line_class = "odd_line";
                        while($lines_orders = $result_orders->fetch_object()){
                    ?>
                    <tr onclick="window.location='../../store/orders/edit.php?edit_hash=<?= md5($lines_orders->id); ?>'" style="cursor:pointer;">
                        <td style="text-align:center"><?= $lines_orders->id; ?></td>
                        <td><?= $lines_orders->created_at; ?></td>
                        <td><?= $lines_orders->shipping_method; ?></td>
                        <td><span style="color:green;" title="Ganhos"><?= $lines_orders->points_earned ?></span> - <span style="color:red;" title="Gastos"><?= $lines_orders->points_spent ?></span></td>
                        <td><?= $lines_orders->status; ?></td>
                        <td><?= Price::output($lines_orders->total) ?></td>
                    </tr>
                  	<?php
                        }
                    ?>
                </table>
                <?php
					}
				?>
            </div>

            <input type="submit" value="Gravar">
            <input type="hidden" name="op" value="update">
        </form>
	</div>

	<?php $template->importScripts(); ?>
</body>
</html>
