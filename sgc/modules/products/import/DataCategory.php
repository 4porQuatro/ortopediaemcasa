<?php

require_once('DataModel.php');


class DataCategory extends DataModel
{
    public $parent_id;
    public $slug;

    public function __construct($parent_id, $title, $language_id, $mysqli)
    {
        $this->mysqli = $mysqli;

        $this->parent_id = $parent_id;
        $this->title = $title;
        $this->slug = createSlug($this->title, 'item_categories', $this->mysqli);
        $this->language_id = $language_id;
    }

    public function exists()
    {
        $rs = $this->mysqli->query("SELECT id FROM item_categories WHERE title = '$this->title'") or die($this->mysqli->error);

        if($rs->num_rows)
        {
            $record = $rs->fetch_object();

            $this->id = $record->id;
        }

        return $rs->num_rows;
    }

    public function insert()
    {
        if(!$this->exists())
        {
            $this->mysqli->query(
                "INSERT INTO item_categories (parent_id, title, slug, language_id, created_at, updated_at)
                VALUES($this->parent_id, '$this->title', '$this->slug', $this->language_id, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
            ) or die('<h4>Inserting category...</h4>' . $this->mysqli->error);

            $this->id = $this->mysqli->insert_id;
        }
    }
}