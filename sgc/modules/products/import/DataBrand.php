<?php

require_once('DataModel.php');


class DataBrand extends DataModel
{
    public function __construct($title, $language_id, $mysqli)
    {
        $this->mysqli = $mysqli;

        $this->title = $title;
        $this->language_id = $language_id;
    }

    public function exists()
    {
        $rs = $this->mysqli->query("SELECT id FROM item_brands WHERE title = '$this->title'") or die($this->mysqli->error);

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
                "INSERT INTO item_brands (title, language_id, created_at, updated_at)
                VALUES('$this->title', $this->language_id, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
            ) or die('<h4>Inserting brand...</h4>' . $this->mysqli->error);

            $this->id = $this->mysqli->insert_id;
        }
    }
}