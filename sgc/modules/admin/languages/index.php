<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');
//get languages
$result_langs = $mysqli->query("SELECT * FROM languages ORDER BY priority ASC;") or die($mysqli->error);
$total_langs = $result_langs->num_rows;
if (isset($_POST['op']) && $_POST['op'] == "update") {
    $mysqli->autocommit(false);
    for ($i = 0; $i < $total_langs; $i++) {
        $lang_id = $_POST['lang_id_' . $i];
        $active = $_POST['active_' . $i];
        $mysqli->query("UPDATE languages SET active = " . $active . " WHERE id = " . $lang_id . ";") or die($mysqli->error);
    }
    $mysqli->commit();

    header("location: index.php?op=success");
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
        <li><a class="active" href="index.php">Idiomas</a></li>
        <li><a href="content_copy.php">Cópia de conteúdos</a></li>
        <li><a href="translator.php">Traduções</a></li>
    </ul>

    <h2>Idiomas</h2>

    <?php
        if (isset($_GET['op']) && $_GET['op'] == 'success') {
            echo '<p class="success">Os registos foram atualizados com sucesso!</p>';
        }
    ?>
    <form name="languages_update_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
        <table class="records_list">
            <?php
                $i = 0;
                while ($lines_langs = $result_langs->fetch_object()) {
            ?>
            <tr>
                <td>
                    <a><?= $lines_langs->language; ?></a>
                    <input type="hidden" name="lang_id_<?= $i; ?>" value="<?= $lines_langs->id; ?>">
                </td>
                <td style="width:70px">
                    <input type="radio" name="active_<?= $i; ?>" id="active1_<?= $i; ?>" value="1" <?php if ($lines_langs->active) echo 'checked'; ?>>
                    <label for="active1_<?= $i; ?>">Ativo</label>
                </td>
                <td style="width:70px">
                    <input type="radio" name="active_<?= $i; ?>" id="active0_<?= $i; ?>" value="0" <?php if (!$lines_langs->active) echo 'checked'; ?>>
                    <label for="active0_<?= $i; ?>">Inativo</label>
                </td>
            </tr>
            <?php
                    $i++;
                }
            ?>
        </table>
        <input class="record_opt_btn" type="submit" value="Guardar">
        <input type="hidden" name="op" value="update">
    </form>
</div>
<?php $template->importScripts(); ?>
</body>
</html>
