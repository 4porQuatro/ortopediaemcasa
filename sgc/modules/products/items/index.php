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
                        <td style="width: 25%;">
                        	<select>
                            	<option value="%">Todas as categorias</option>

                                <?php
                                    $categories_rs = $mysqli->query("SELECT id, parent_id, title FROM item_categories WHERE language_id = " . $language_id . " ORDER BY priority");

                                    if($categories_rs->num_rows)
                                    {
                                        while($category = $categories_rs->fetch_object()){
                                            $categories_arr[$category->parent_id][$category->id] = $category->title;
                                        }

                                        printTreeOptions($categories_arr);
                                    }
                                ?>
                            </select>
                        </td>
                        <td style="width: 25%;">
                            <select>
                                <option value="%">Todas as marcas</option>
                                <?php
                                    $result_filters = $mysqli->query("SELECT * FROM item_brands WHERE language_id = " . $language_id . " ORDER BY title") or die($mysqli->error);
                                    while($filter = $result_filters->fetch_object()){
                                ?>
                                    <option value="<?= $filter->id ?>"><?= $filter->title ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td style="width: 25%;">
                            <select>
                                <option value="%">Estado</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </td>
                        <td>
                            <select>
                                <option value="%">Destaque</option>
                                <option value="1">Com destaque</option>
                                <option value="0">Sem destaque</option>
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
                table: 'items',
                primary_key: 'id',
                language_id: <?= $_SESSION['sgc_language_id']; ?>,
                filters_arr: ['item_category_id', 'item_brand_id', 'active', 'highlight'],
                sort_field: 'priority',
    			sortable: true
            });
        });
        </script>
    </body>
</html>
