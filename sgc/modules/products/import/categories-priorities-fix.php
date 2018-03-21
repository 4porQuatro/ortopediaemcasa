<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

function recursiveTree(array $arr, $mysqli, $parent = NULL, $level = 0, $priority = 1){
    $indent = "&nbsp;&nbsp;&nbsp;&nbsp;";

    foreach($arr[$parent] as $id => $val){
        $mysqli->query("UPDATE item_categories SET priority = " . $priority . " WHERE id = " . $id) or die($mysqli->error);
        $priority++;

        if(isset($arr[$id])){
            recursiveTree($arr, $mysqli, $id, $level + 1);
        }
    }
}

// get all categories from database
$categories_rs = $mysqli->query("SELECT id, parent_id, title FROM item_categories WHERE language_id = " . $language_id . " ORDER BY priority");

if($categories_rs->num_rows) {
    while ($category = $categories_rs->fetch_object()) {
        $categories_arr[$category->parent_id][$category->id] = $category->title;
    }

    recursiveTree($categories_arr, $mysqli);
}