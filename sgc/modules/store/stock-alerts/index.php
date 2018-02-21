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
	    	<h2>Lista de projetos</h2>

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

	        <div class="records_pane">
	        	<table>
	                <tr>
	                	<td style="width:50%;">
	                    	<select class="filter">
	                            <option value="%">Todos os utilizadores</option>
	                            <?php
									$result_filter = $mysqli->query("SELECT id, billing_name FROM users ORDER BY billing_name ASC") or die($mysqli->error);

									if($result_filter->num_rows){
										while($rec_filter = $result_filter->fetch_object()){
								?>
	                            <option value="<?= $rec_filter->id; ?>"><?= $rec_filter->billing_name; ?></option>
	                            <?php
										}
									}
	                            ?>
	                        </select>
	                    </td>
						<td>
	                    	<select class="filter">
	                            <option value="%">Todos os produtos</option>
	                            <?php
									$result_filter = $mysqli->query("SELECT item_id, title FROM items ORDER BY priority ASC") or die($mysqli->error);

									if($result_filter->num_rows){
										while($rec_filter = $result_filter->fetch_object()){
								?>
	                            <option value="<?= $rec_filter->item_id; ?>"><?= $rec_filter->title; ?></option>
	                            <?php
										}
									}
	                            ?>
	                        </select>
	                    </td>
	                </tr>
	            </table>

	        	<div class="results_pane"></div>
	        </div>
	    </div>

	    <?php $template->importScripts(); ?>
		<script type="text/javascript" src="../../../assets/js/jquery.cookies.js"></script>
		<script type="text/javascript" src="List/controller.js"></script>
	</body>
</html>
