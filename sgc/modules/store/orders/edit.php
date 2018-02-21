<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	use App\Lib\Store\Price;

	$table = "store_orders";
	$pk = "id";

	if(isset($_GET['edit_hash'])){
		$rs_order = $mysqli->query(
			"SELECT t1.*, SUM(t2.price) AS 'total_price', t3.billing_name AS 'username', t4.title As 'state'
			FROM $table AS t1, store_order_items AS t2, users AS t3, store_order_states AS t4
			WHERE t1." . $pk . " = t2.order_id
			AND t1.user_id = t3.id
			AND t1.state_id = t4.id
			AND md5(t1." . $pk . ") = '" . $mysqli->real_escape_string($_GET['edit_hash']) . "'
			GROUP BY t2." . $pk . "
			LIMIT 0, 1;"
		) or die($mysqli->error);

		$rec_order = $rs_order->fetch_object();

		if(!$rs_order->num_rows){
			header("location: index.php");
			exit;
		}

		/*
		*	GET ITEMS LIST
		*/
		$rs_prods = $mysqli->query("SELECT * FROM store_order_items WHERE order_id = '" . $rec_order->$pk . "'") or die($mysqli->error);

		if(isset($_POST['op']) && $_POST['op'] == "update"){
			$state_id = $_POST['state_id'];
			$mysqli->autocommit(false);

			// update record
			$stmt_update = $mysqli->prepare("UPDATE " . $table . " SET state_id = ? WHERE " . $pk . " = " . $rec_order->$pk) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param(
				"i",
				$state_id
			);
			$stmt_update->execute() or die('<h3>Updating record...</h3>' . $stmt_update->error);

			/**
			 *  if order is canceled, we must subtract user points
			 */
			if($state_id == 3){
				$mysqli->query("UPDATE users SET points = points - " . $rec_order->points . " WHERE id = " . $rec_order->user_id);
			}

			$mysqli->commit();

			header("location: index.php?edit=success");
			exit;
		}
	}else{
		header("location: index.php");
		exit;
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

        <h2>Editar registo nr.º <?= $rec_order->$pk; ?></h2>
		<?php
			if(isset($errors) && !empty($errors))
				echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
		?>

        <ul id="form_menu">
            <li>Dados da Encomenda</li>
        </ul>
        <form class="form_model" name="update_order_state_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
            <div class="form_pane">
                <h3>Resumo</h3>
                <table>
                	<tr>
                    	<th style="width:1%">Data:</th>
                        <td><?= $rec_order->created_at ?></td>
                    </tr>
                	<tr>
                    	<th style="width:1%">Estado:</th>
                        <td><?= $rec_order->state ?></td>
                    </tr>

                	<tr>
                    	<th style="width:1%">Método de envio:</th>
                        <td><?= $rec_order->shipping_method ?></td>
                    </tr>
                	<tr>
                    	<th>Utilizador:</th>
                        <td><a href="../../private-area/users/edit.php?edit_hash=<?= md5($rec_order->user_id); ?>" target="_blank"><?= $rec_order->username; ?> &rarr;</a></td>
                    </tr>
                	<tr>
                    	<th>Pontos:</th>
                        <td><span style="color:green;" title="Ganhos">Ganhos: <?= $rec_order->points_earned ?></span> | <span style="color:red;" title="Gastos">Gastos: <?= $rec_order->points_spent ?></span></td>
                    </tr>
                </table>

                <h3>Items</h3>
                <?php
					if(!$rs_prods->num_rows){
				?>
                <p class="info">Não exitem items registadas para esta encomenda.</p>
                <?php
					}else{
				?>
                <table class="items_list">
                	<thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Cor</th>
                            <th>Tamanho</th>
                            <th style="width:10%;text-align:center">Qt.</th>
                            <th style="width:10%;text-align:center">Preço (Un)</th>
                            <th style="width:1%;text-align:right">Sub-total</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
                            $items_total = 0;
                            while($order_item = $rs_prods->fetch_object()){
                                $items_sub_total = $order_item->price * $order_item->quantity;
                                $items_total += $items_sub_total;

                                $attributes = json_decode($order_item->attributes)
                        ?>
                        <tr style="cursor:pointer;">
                            <td><?= $order_item->name ?></td>
                            <td><?= $attributes->color->name ?></td>
                            <td><?= $attributes->size->name ?></td>
                            <td style="text-align:center;"><?= $order_item->quantity ?></td>
                            <td style="text-align:center;"><?= Price::output($order_item->price) ?></td>
                            <td style="text-align:right;"><?= Price::output($items_sub_total) ?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <b>Total Items:</b><br>
                                <b>Envio (<?= $rec_order->shipping_method ?>):</b><br>
                                <b>Desconto voucher:</b><br>
                                <b>Desconto pontos:</b><br>
                                <b><big>Total:</big></b>
                            </td>
                            <td style="text-align:right">
                                <?= Price::output($items_total) ?><br>
                                <?= Price::output($rec_order->shipping_cost) ?><br>
                                <?= ($rec_order->voucher_discount > 0) ? '-' . Price::output($rec_order->voucher_discount) : 0 ?><br>
                                <?= ($rec_order->points_discount > 0) ? '-' . Price::output($rec_order->points_discount) : 0 ?><br>
                                <b><big><?= Price::output($rec_order->total) ?></big></b>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php
					}
				?>

                <h3>Observações sobre o envio</h3>
				<?= !empty($rec_order->shipping_observations) ? $rec_order->shipping_observations : '---' ?>

                <hr class="hline">

                <h3>Estado da encomenda</h3>
                <table>
                    <tr>
                        <th style="width:200px">Estado</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="state_id">
                              <?php
								$select_id = (isset($_POST['state_id'])) ? $_POST['state_id'] : $rec_order->state_id;
								$result_state = $mysqli->query("SELECT id, title FROM store_order_states WHERE language_id = " . $language_id);
								if($result_state->num_rows){
									while($row_state = $result_state->fetch_object()){
										$selected = ($select_id == $row_state->id) ? ' selected' : NULL;
							?>
							<option value="<?= $row_state->id; ?>"<?= $selected; ?>><?= $row_state->title; ?></option>
							<?php
									}
								}
							?>
                            </select>
                        </td>
                        <td></td>
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
