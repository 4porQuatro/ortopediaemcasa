<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

require_once('../../../core/lib/SimpleXLSX/SimpleXLSX.php');
require_once('DataImport.php');
require_once('DataItem.php');
require_once('DataBrand.php');
require_once('DataCategory.php');

$language_id = 1;
$tax_id = 1;

$di = new DataImport(
    [
        "files/categoria-ajudas-tecnicas.xlsx",
        "files/categoria-calcado.xlsx",
        "files/categoria-cirurgia-estetica.xlsx",
        "files/categoria-cuidado-pessoal.xlsx",
        "files/categoria-flebologia.xlsx",
        "files/categoria-incontinencia.xlsx",
        "files/categoria-mobilidade.xlsx",
        "files/categoria-mobiliario.xlsx",
        "files/categoria-ortopedia.xlsx",
        "files/categoria-podologia.xlsx",
        "files/categoria-prevencao-de-escaras.xlsx",
        "files/categoria-puericultura-e-maternidade.xlsx",
    ]
);

foreach ($di->getFiles() as $file) {
    echo '<h3>A importar dados do ficheiro "' . $file . '"</h3>';

    if ($xlsx = SimpleXLSX::parse($file)) {
        $mysqli->autocommit(false);

        foreach ($xlsx->rows() as $row => $columns) {
            // If the row is empty, we will skip it
            if (DataImport::rowIsEmpty($columns))
            {
                continue;
            }
            // If the row has a category, we will manage the categories tree, as well the database
            else if (DataImport::rowHasCategory($columns))
            {
                $categories_tree = DataImport::getCategoriesTree($columns[0]);

                echo '<h4>Árvore de categorias</h4>';
                echo '<pre>';
                print_r($categories_tree);
                echo '</pre>';

                $parent_id = 'NULL';

                if (sizeof($categories_tree)) {
                    foreach ($categories_tree as $category) {
                        $data_category = new DataCategory($parent_id, $category, $language_id, $mysqli);
                        $data_category->insert();

                        $parent_id = $data_category->id;
                    }
                }
            }
            // The row has an item!
            else if($columns[0] != 'Código')
            {
                $brand_title = (empty($columns[1])) ? 'Marca X' : $columns[1];
                $data_brand = new DataBrand($brand_title, $language_id, $mysqli);
                $data_brand->insert();

                // add more data to the columns array
                $columns[] = $data_brand->id;
                $columns[] = $data_category->id;
                $columns[] = $tax_id;
                $columns[] = $language_id;
                $data_item = new DataItem($columns, $mysqli);

                $data_item->insert();
            }
        }

        $mysqli->commit();
    } else {
        echo SimpleXLSX::parse_error();
    }

    echo '<hr>';
}