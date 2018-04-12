<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

$table = "items";
$pk = "id";

$entity = entity($mysqli, $table);
$entity->mapDBValues($pk, $_GET['edit_hash'], $language_id);

if(!$entity->getDBValue($pk)){
    header("location: index.php");
    exit;
}
if(isset($_POST['op']) && $_POST['op'] == "update") {
    // delete old attribute values
    $mysqli->query("DELETE FROM item_item_attribute_value WHERE item_id = " . $entity->getDBValue($pk)) or die($mysqli->error);

    // insert new permissions
    $stmt_permissions = $mysqli->prepare("INSERT INTO item_item_attribute_value(item_id, item_attribute_value_id) VALUES (" . $entity->getDBValue($pk) . ", ?)") or die('<h3>Preparign to insert permissions...</h3>' . $mysqli->error);
    $stmt_permissions->bind_param('i', $id);
    foreach($_POST['item_attribute_value_id'] as $id=>$val){
        if(isset($_POST['item_attribute_value_id'][$id])){
            $stmt_permissions->execute() or die('<h3>Inserting permissions...</h3>' . $mysqli->error);
        }
    }

    /*......................................................................................*/

    $mysqli->commit();
    header("location: " . $_SERVER['PHP_SELF'] . "?edit_hash=" . $_GET['edit_hash'] . "&edit=success");
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
    		<a class="record_opt_btn" href="index.php">&larr; Voltar</a>
        </div>

    	<h2>Editar registo nr.º <?= $entity->getDBValue($pk); ?></h2>

        <h3><?= $entity->getDBValue('title') ?></h3>

		<?php
			if(isset($_GET['edit']) && $_GET['edit'] == 'success'){
        ?>
        <p class="success"><b>O registo foi editado com sucesso.</b></p>
        <?php
            }
		?>

        <ul id="form_menu">
            <li>Atributos</li>
        </ul>

        <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
        	<div class="form_pane">
                <?php
                    /*
                     * Fetch the attributes available for this item sub-category
                     */
                    $rs_attr_types = $mysqli->query("SELECT * FROM item_attribute_types WHERE item_category_id = " . $entity->getDBValue('item_category_id')) or die($mysqli->error);
                    if($rs_attr_types->num_rows) {
                ?>
                <div class="permissions_pane">
                    <ol>
                        <?php
                            while($attr_type = $rs_attr_types->fetch_object()) {
                        ?>
                        <li>
                            <input type="checkbox" id="item_attribute_type[<?= $attr_type->id ?>]">
                            <label for="item_attribute_type[<?= $attr_type->id ?>]"><?= $attr_type->title ?></label>
                            <?php
                                    $rs_attr_values = $mysqli->query("SELECT * FROM item_attribute_values WHERE item_attribute_type_id = " . $attr_type->id) or die($mysqli->error);
                                    if($rs_attr_values->num_rows) {
                            ?>
                            <ol>
                                <?php
                                        while ($attr_val = $rs_attr_values->fetch_object()) {
                                            // check if this attribute value is associated to this item
                                            $rs_item_attr = $mysqli->query("SELECT * FROM item_item_attribute_value WHERE item_id = " . $entity->getDBValue($pk) . " AND item_attribute_value_id = " . $attr_val->id) or die($mysqli->error);
                                            $checked = ($rs_item_attr->num_rows || isset($_POST['item_attribute_value_id'][$attr_val->id])) ? ' checked' : '';
                                ?>
                                <li>
                                    <input type="checkbox" name="item_attribute_value_id[<?= $attr_val->id ?>]" id="item_attribute_value_id[<?= $attr_val->id ?>]" value="<?= $attr_val->id ?>"<?= $checked ?>>
                                    <label for="item_attribute_value_id[<?= $attr_val->id ?>]"><?= $attr_val->title ?></label>
                                </li>
                                <?php
                                    }
                                ?>
                            </ol>
                            <?php
                                }
                            ?>
                        </li>
                        <?php
                            }
                        ?>
                    </ol>
                </div>
                <?php
                    }
                ?>
            </div>

            <input type="submit" value="Gravar">
            <input type="hidden" name="op" value="update">
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
