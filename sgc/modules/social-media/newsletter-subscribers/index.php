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
				if(isset($_GET['import']) && $_GET['import'] == "success")
					echo '<p class="success">A importação de dados foi concluída com sucesso!</p>';
				if(isset($errors) && $errors != "")
					echo '<p class="error"><b>Durante a importação do ficheiro, foram detetados erros nas seguintes linhas:</b><br>' . $errors . '</p>', PHP_EOL;
			?>

	        <?php
	        	if(isset($_GET['insert']) && $_GET['insert'] == "success"){
			?>
	        <script>
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
	        <script>
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
	    <script src="../../../assets/js/jquery.cookies.js"></script>
	    <script src="../../../assets/plugins/RecordsList/records_list.js"></script>
	    <script>
	    $(document).ready(function(){
		    $('.records_pane').recordsList({
	            table: 'newsletter_subscribers',
	            primary_key: 'id',
				columns: ['email'],
	            filters_arr: ['active'],
	            sort_field: 'email'
			});
	    });
	    </script>
	</body>
</html>
