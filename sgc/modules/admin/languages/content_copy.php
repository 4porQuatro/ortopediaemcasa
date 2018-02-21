<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

// get modules
$result_modules = $mysqli->query("SELECT * FROM sgc_modules WHERE active ORDER BY priority ASC") or die($mysqli->error);
$total_modules = $result_modules->num_rows;

// uploads folder
$images_folder = "../../../../public/uploads/images/";
$docs_folder = "../../../../public/uploads/files/";
$videos_folder = "../../../../public/uploads/videos/";

if (isset($_POST['op']) && $_POST['op'] == "copy") {
    $mysqli->autocommit(false);

    $orig_lang_id = $_POST['orig_lang'];
    $dest_lang_id = $_POST['dest_lang'];


    // get dest language data
    $result_dest_lang = $mysqli->query("SELECT * FROM languages WHERE id = " . $dest_lang_id) or die('<h4>Getting destination language data</h4>' . $mysqli->error);
    $line_dest_lang = $result_dest_lang->fetch_object();

    $dest_lang_iso = $line_dest_lang->iso;


    if (isset($_POST['module_id']))
    {
        foreach ($_POST['module_id'] as $module_id)
        {
            //get module's tables
            $result_tables = $mysqli->query("SELECT * FROM sgc_modules_tables WHERE module_id = $module_id AND active ORDER BY priority ASC");
            while ($lines_tables = $result_tables->fetch_object()) {
                /* set subfolder */
                $images_subfolder = $images_folder . $lines_tables->name . "/";
                $docs_subfolder = $docs_folder . $lines_tables->name . "/";
                $videos_subfolder = $videos_folder . $lines_tables->name . "/";

                // create temporary table and copy the records with the origin language id
                $mysqli->query("CREATE TEMPORARY TABLE tmp_" . $lines_tables->name . " SELECT * FROM " . $lines_tables->name . " WHERE language_id = " . $orig_lang_id);
                // update language id in temporary table
                $mysqli->query("UPDATE tmp_" . $lines_tables->name . " SET language_id = " . $dest_lang_id);

                // get database
                $result_db = $mysqli->query("SELECT DATABASE()");
                $line_db = $result_db->fetch_row();

                // get unique keys from table
                $result_uniques = $mysqli->query("SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_NAME = '" . $lines_tables->name . "' AND TABLE_SCHEMA = '" . $line_db[0] . "' AND CONSTRAINT_TYPE = 'UNIQUE';");

                // concat prefix to unique keys values
                if ($result_uniques->num_rows) {
                    while ($lines_uniques = $result_uniques->fetch_object()) {
                        $mysqli->query("UPDATE tmp_" . $lines_tables->name . " SET " . $lines_uniques->CONSTRAINT_NAME . " = CONCAT('" . $dest_lang_iso . "_', " . $lines_uniques->CONSTRAINT_NAME . ");") or die($mysqli->error);
                    }
                }

                // insert copied contents to original table
                $mysqli->query("INSERT IGNORE INTO " . $lines_tables->name . " SELECT * FROM tmp_" . $lines_tables->name . ";");

                // result
                $rs_fields = $mysqli->query("SHOW COLUMNS FROM " . $lines_tables->name) or die('<h4>Getting table fields</h3>' . $mysqli->error);
                if ($rs_fields->num_rows) {
                    // get primary key
                    $table_pk = entity($mysqli, $lines_tables->name)->getPK();

                    while ($column = $rs_fields->fetch_object()) {
                        /**
                         * Check for images fields
                         */
                        if (strpos($column->Field, 'images') !== false) {
                            $rs_files = $mysqli->query("SELECT " . $table_pk . ", " . $column->Field . " FROM " . $lines_tables->name . " WHERE language_id = " . $orig_lang_id);
                            while ($line_files = $rs_files->fetch_object()) {
                                $files_arr = json_decode($line_files->{$column->Field});

                                if (sizeof($files_arr)) {
                                    foreach ($files_arr as $file) {
                                        if (file_exists($images_subfolder . $file->source)) {
                                            copy($images_subfolder . $file->source, $images_subfolder . $dest_lang_iso . "_" . $file->source);
                                        }

                                        $file->source = $dest_lang_iso . "_" . $file->source;
                                    }
                                }

                                // update column
                                $rs_update_file = $mysqli->query(
                                    "UPDATE " . $lines_tables->name . "
										SET " . $column->Field . " = '" . json_encode($files_arr) . "'
										WHERE " . $table_pk . " = " . $line_files->$table_pk . "
										AND language_id = " . $dest_lang_id
                                ) or die('<h4>Updating json on file column</h4>' . $mysqli->error);
                            }
                        }

                        /**
                         *  Check for docs fields
                         */
                        if (strpos($column->Field, 'docs') !== false) {
                            $rs_files = $mysqli->query("SELECT " . $table_pk . ", " . $column->Field . " FROM " . $lines_tables->name . " WHERE language_id = " . $orig_lang_id);
                            while ($line_files = $rs_files->fetch_object()) {
                                $files_arr = json_decode($line_files->{$column->Field});

                                if (sizeof($files_arr)) {
                                    foreach ($files_arr as $file) {
                                        if (file_exists($docs_subfolder . $file->source)) {
                                            copy($docs_subfolder . $file->source, $docs_subfolder . $dest_lang_iso . "_" . $file->source);
                                        }

                                        $file->source = $dest_lang_iso . "_" . $file->source;
                                    }
                                }

                                // update column
                                $rs_update_file = $mysqli->query(
                                    "UPDATE " . $lines_tables->name . "
										SET " . $column->Field . " = '" . json_encode($files_arr) . "'
										WHERE " . $table_pk . " = " . $line_files->$table_pk . "
										AND language_id = " . $dest_lang_id
                                ) or die('<h4>Updating json on file column</h4>' . $mysqli->error);
                            }
                        }

                        /**
                         *  Check for videos fields
                         */
                        if (strpos($column->Field, 'videos') !== false) {
                            $rs_files = $mysqli->query("SELECT " . $table_pk . ", " . $column->Field . " FROM " . $lines_tables->name . " WHERE language_id = " . $orig_lang_id);
                            while ($line_files = $rs_files->fetch_object()) {
                                $files_arr = json_decode($line_files->{$column->Field});

                                if (sizeof($files_arr)) {
                                    foreach ($files_arr as $file) {
                                        if (file_exists($videos_subfolder . $file->source)) {
                                            copy($videos_subfolder . $file->source, $videos_subfolder . $dest_lang_iso . "_" . $file->source);
                                        }

                                        $file->source = $dest_lang_iso . "_" . $file->source;
                                    }
                                }

                                // update column
                                $rs_update_file = $mysqli->query(
                                    "UPDATE " . $lines_tables->name . "
										SET " . $column->Field . " = '" . json_encode($files_arr) . "'
										WHERE " . $table_pk . " = " . $line_files->$table_pk . "
										AND language_id = " . $dest_lang_id
                                ) or die('<h4>Updating json on file column</h4>' . $mysqli->error);
                            }
                        }
                    }
                }
            }
        }
    }

    $mysqli->commit();
    header("location: " . $_SERVER['PHP_SELF'] . "?copy=success");
    exit;
}

//get languages
$result_langs = $mysqli->query("SELECT * FROM languages ORDER BY priority") or die($mysqli->error);
$total_langs = $result_langs->num_rows;
//build languages array
if ($total_langs) {
    $langs_orig_arr = array();
    while ($lang = $result_langs->fetch_object()) {
        $langs_arr[] = $lang;
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php $template->importMetaTags(); ?>
    <?php $template->importHeadTitle(); ?>
    <?php $template->importStyles(); ?>
    <?php $template->importHeadScripts(); ?>
    <style>
        .records_list td,
        .records_list th{
            padding: 12px 10px !important;
        }
    </style>
</head>
<body>
    <?php $template->printSideBar($mysqli); ?>

    <div id="data_container">
        <ul class="section_menu">
            <li><a href="index.php">Idiomas</a></li>
            <li><a class="active" href="content_copy.php">Cópia de conteúdos</a></li>
            <li><a href="translator.php">Traduções</a></li>
        </ul>

        <h2>Cópia de conteúdos</h2>

        <?php
            if (!$total_modules || $result_langs->num_rows < 2) {
        ?>
        <p class="info">Não é possível copiar conteúdos, pois não existem módulos configurados ou idiomas
            registados.</p>
        <?php
            } else {
        ?>
            <h3>Selecione os módulos que pretende copiar.</h3>
        <?php
            if (isset($_GET['copy'])) {
                if ($_GET['copy'] == 'error')
                    echo '<p class="error">A cópia de conteúdos falhou!<br>Por favor, tente novamente.</p>';
                if ($_GET['copy'] == 'success')
                    echo '<p class="success">A cópia de conteúdos foi concluída com sucesso!</p>';
            }
        ?>

        <ul id="form_menu">
            <li>Cópia de conteúdos</li>
        </ul>

        <form class="form_model" name="contents_copy" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
            <div class="form_pane">
                <table class="records_list">
                    <tr>
                        <th class="no_style" style="width:36px;"></th>
                        <th>Módulo</th>
                    </tr>
                    <?php
                        while ($lines_modules = $result_modules->fetch_object()) {
                    ?>
                    <tr>
                        <td style="text-align:center"><input type="checkbox" name="module_id[]"
                                                             value="<?= $lines_modules->id ?>"></td>
                        <td><?= $lines_modules->title; ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>

                <table>
                    <tr>
                        <th>Idioma de origem</th>
                        <th>Idioma de destino</th>
                    </tr>
                    <tr>
                        <td>
                            <?php if (sizeof($langs_arr)) { ?>
                            <select name="orig_lang">
                                <?php
                                    foreach ($langs_arr as $lang) {
                                ?>
                                <option value="<?= $lang->id ?>"><?= $lang->language ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (sizeof($langs_arr)) { ?>
                            <select name="dest_lang">
                                <?php
                                    foreach ($langs_arr as $lang) {
                                ?>
                                <option value="<?= $lang->id ?>"><?= $lang->language ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <input type="submit" value="Copiar"
                   onclick="return confirm('A operação que está prestes a realizar é ireversível! Tem a certeza que pretende continuar?')">
            <input type="hidden" name="op" value="copy">
        </form>
        <?php
            }
        ?>
    </div>

    <?php $template->importScripts(); ?>
</body>
</html>
