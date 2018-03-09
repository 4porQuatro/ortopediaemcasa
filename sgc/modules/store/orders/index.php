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
        <script>
        $(document).ready(function(){
        	setTimeout(function(){$("p#insert_op").fadeOut(500);}, 3000);
        });
        </script>
        <?php
        		echo '<p class="success" id="insert_op">O registo foi inserido com sucesso!</p>';
        	}
        ?>

        <div class="records_pane">
        	<div class="input_wrapper"><input type="text" name="search_value" placeholder="Pesquisar por número de encomenda ou referência do anúncio"></div>

        	<table>
                <tr>
                	<td style="width:33%">
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
                    <td style="width:33%">
                        <select class="filter">
                            <option value="%">Todos os estados</option>
                            <?php
								$result_filter = $mysqli->query("SELECT id, title FROM store_order_states WHERE language_id = " . $language_id . " ORDER BY id ASC") or die($mysqli->error);

								if($result_filter->num_rows){
									while($rec_filter = $result_filter->fetch_object()){
							?>
							<option value="<?= $rec_filter->id; ?>"><?= $rec_filter->title; ?></option>
                            <?php
									}
								}
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td width="50%">
                        <select class="filter">
                            <option value="%">Todos os anos</option>
                        	<?php
								$result = $mysqli->query("SELECT MIN(YEAR(created_at)) AS 'first_year', MAX(YEAR(created_at)) AS 'last_year' FROM store_orders LIMIT 0, 1") or die($mysqli->error);
								if($result->num_rows){
									$line = $result->fetch_object();
									for($year = $line->first_year; $year <= $line->last_year; $year++){
							?>
                            <option value="<?= $year; ?>"><?= $year; ?></option>
                            <?php
									}
								}
							?>
                        </select>
                    </td>
                    <td>
                        <select class="filter">
                            <option value="%">Todos os meses</option>
                            <?php
								$months_arr = Date::getMonthsArray('pt');

								foreach($months_arr as $key=>$month){
							?>
                            <option value="<?= ($key + 1); ?>"><?= $month; ?></option>
                            <?php
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
	<script src="../../../assets/js/jquery.cookies.js"></script>
	<script src="List/controller.js"></script>
</body>
</html>
