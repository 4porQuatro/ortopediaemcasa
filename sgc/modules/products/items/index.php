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
                        <td style="width:25%;">
                        	<select>
                            	<option value="%">Todas as categorias</option>
    							<?php
    								$rs_types = $mysqli->query("SELECT id, title FROm items_types WHERE language_id = " . $language_id . " ORDER BY priority ASC") or die($mysqli->error);
    								if($rs_types->num_rows){
    									while($rec_type = $rs_types->fetch_object()){
    							?>
                                <optgroup label="<?= $rec_type->title ?>">
                                	<?php
    									$rs_cats = $mysqli->query("SELECT * FROM items_categories WHERE language_id = " . $language_id . " AND type_id = " . $rec_type->id . " ORDER BY priority ASC") or die($mysqli->error);
    									if($rs_cats->num_rows){
    										while($rec_cat = $rs_cats->fetch_object()){
    								?>
                                    <option value="<?= $rec_cat->id ?>"><?= $rec_cat->title ?></option>
                                    <?php
    										}
    									}
    								?>
                                </optgroup>
                                <?php
    									}
                                    }
                                ?>
                            </select>
                        </td>
                        <td style="width:25%;">
                            <select>
                                <option value="%">Estado</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </td>
                        <td style="width:25%">
                            <select>
                                <option value="%">Destaque</option>
                                <option value="1">Com destaque</option>
                                <option value="0">Sem destaque</option>
                            </select>
                        </td>
                    	<td>
                            <select>
                                <option value="%">Popular</option>
                                <option value="1">É popular</option>
                                <option value="0">Não é popular</option>
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
                table: 'items',
                primary_key: 'id',
                language_id: <?= $_SESSION['sgc_language_id']; ?>,
                filters_arr: ['category_id','active','highlight','popular'],
                sort_field: 'priority',
    			sortable: true
            });
        });
        </script>
    </body>
</html>
