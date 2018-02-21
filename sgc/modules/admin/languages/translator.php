<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

// get language
$rs_language = $mysqli->query("SELECT * FROM languages WHERE id = " . $language_id . " LIMIT 0, 1");
if($rs_language->num_rows){
    $language = $rs_language->fetch_object();
}

// declare & initialize variables
$file_path = dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/lang/' . $language->iso . '/app.php';
$expressions_arr = include($file_path);
array_multisort($expressions_arr);

if(isset($_POST['op'])){
    unset($_POST['op']);

    $file_content = '<?php return ' . var_export($_POST, true) . ';';
    file_put_contents($file_path, $file_content);

    header("location: " . $_SERVER['PHP_SELF'] . "?edit=success");
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
        <ul class="section_menu">
            <li><a href="index.php">Idiomas</a></li>
            <li><a href="content_copy.php">Cópia de conteúdos</a></li>
            <li><a class="active" href="translator.php">Traduções</a></li>
        </ul>

        <h2>Traduções</h2>

        <?php
            if(isset($_GET['edit']) && $_GET['edit'] == "success"){
        ?>
        <script>
            $(document).ready(function(){
                setTimeout(function(){$("p#edit_op").fadeOut(500);}, 3000);
            });
        </script>
        <p class="success" id="edit_op">As alterações foram gravadas com sucesso!</p>
        <?php
            }
        ?>

        <?php
            if(!sizeof($expressions_arr)){
        ?>
        <p class="info">
            Não foram encontradas expressões para o idioma <b><?= $language->language ?>.</b>
        </p>
        <?php
            }else{
        ?>
        <form name="edit_translations_form" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
            <table class="records_list">
                <?php
                foreach($expressions_arr as $key=>$val){
                    ?>
                    <tr>
                        <td>
                            <textarea name="<?= $key ?>"><?= $val ?></textarea>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <input type="hidden" name="op" value="update">
            <input class="record_opt_btn" type="submit" value="Guardar">
        </form>
        <?php
            }
        ?>
    </div>

    <?php $template->importScripts(); ?>
</body>
</html>