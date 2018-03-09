<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

// get all items from database
$rs_items = $mysqli->query("SELECT * FROM items") or die($mysqli->error);

if($rs_items->num_rows)
{
    while($item = $rs_items->fetch_object())
    {
        $filename = dirname($_SERVER['DOCUMENT_ROOT']) . "/public/uploads/images/items/" . $item->reference . ".*";
        $files = glob($filename);

        if(!empty($files))
        {
            $db_filename = array_shift($files);

            $file_path = explode('/' , $db_filename);

            $db_filename = $file_path[sizeof($file_path) - 1];
        }

        $images = '[{"filename":"' . $db_filename . '","source":"' . $db_filename . '","title":"' . $item->title . '"}]';

        // update images
        $mysqli->query("UPDATE items SET list_images = '" . $images . "', detail_images = '" . $images . "' WHERE id = " . $item->id) or die($mysqli->error);
    }
}