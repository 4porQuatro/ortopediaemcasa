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
                    <td>
                        <select>
                            <option value="%">Estado</option>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
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
            table: 'users',
            primary_key: 'id',
            filters_arr: ['active'],
			columns: ['billing_name'],
            sort_field: 'billing_name'
        });
    });
    </script>
</body>
</html>
