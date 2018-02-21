<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');
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
    		<a class="record_opt_btn" href="new.php">Criar registo &rarr;</a>
        </div>

    	<h2>Lista de registos</h2>

        <?php
        	if(isset($_GET['insert']) && $_GET['insert'] == "success"){
		?>
        <script type="text/javascript">
		$(document).ready(function(){
			setTimeout(function(){$("p#insert_op").fadeOut(500);}, 3000);
		});
        </script>
        <?php
				echo '<p class="success" id="insert_op">O registo foi inserido com sucesso!</p>';
			}
		?>

        <?php
        	if(isset($_GET['edit']) && $_GET['edit'] == "success"){
		?>
        <script type="text/javascript">
		$(document).ready(function(){
			setTimeout(function(){$("p#edit_op").fadeOut(500);}, 3000);
		});
		</script>
        <?php
				echo '<p class="success" id="edit_op">O registo foi atualizado com sucesso!</p>';
			}
		?>

        <div class="records_pane">
        	<table>
            	<tr>
                    <td style="width:20%">
                    	<select>
                        	<option value="%">Todos os produtos</option>
							<?php
                                $result_filters = $mysqli->query("SELECT id, title FROM items WHERE language_id = " . $language_id . " ORDER BY priority") or die($mysqli->error);
                                while($filter = $result_filters->fetch_object()){
                            ?>
                            <option value="<?= $filter->id ?>"><?= $filter->title ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                    <td style="width:20%">
                    	<select>
                        	<option value="%">Todos os utilizadores</option>
							<?php
                                $result_filters = $mysqli->query("SELECT id, billing_name FROM users ORDER BY billing_name") or die($mysqli->error);
                                while($filter = $result_filters->fetch_object()){
                            ?>
                            <option value="<?= $filter->id; ?>"><?= $filter->billing_name; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                	<td style="width:50%">
                        <select>
                            <option value="%">Todos os estados</option>
                            <option value="1">Aprovados</option>
                            <option value="0">Por aprovar</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
	</div>

	<?php $template->importScripts(); ?>
    <script type="text/javascript" src="../../../assets/js/jquery.cookies.js"></script>
    <script type="text/javascript" src="../../../assets/plugins/RecordsList/records_list.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.records_pane').recordsList({
            table: 'item_testimonials',
            primary_key: 'id',
            language_id: <?= $_SESSION['sgc_language_id']; ?>,
			columns: ['created_at'],
            filters_arr: ['item_id', 'user_id', 'active'],
            sort_field: 'created_at',
			sort_order: 'DESC'
        });
    });
    </script>
</body>
</html>
