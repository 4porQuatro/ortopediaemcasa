<?php

require_once('DataModel.php');


class DataItem extends DataModel
{
    public $reference;
    public $brand_id;
    public $category_id;
    public $brand;
    public $content;
    public $price;
    public $weight;
    public $slug;
    public $tax_id;

    public function __construct($data_arr, $mysqli)
    {
        $this->mysqli = $mysqli;

        $this->reference = $data_arr[0];
        $this->title = $data_arr[2];
        $this->content = $this->mysqli->real_escape_string($data_arr[3]);
        $this->price = $data_arr[4];
        $this->weight = (empty($data_arr[5])) ? 0 : $data_arr[5] / 1000;
        $this->brand_id = $data_arr[6];
        $this->category_id = $data_arr[7];
        $this->tax_id = $data_arr[8];
        $this->language_id = $data_arr[9];

        $this->slug = createSlug($this->title, 'items', $mysqli);
    }

    public function exists()
    {
        $rs = $this->mysqli->query("SELECT id FROM items WHERE reference = '$this->reference'") or die($this->mysqli->error);

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
                "INSERT INTO items(language_id, reference, title, slug, item_brand_id, item_category_id, content, price, weight, tax_id, created_at, updated_at)
                              VALUES($this->language_id, '$this->reference', '$this->title', '$this->slug', $this->brand_id, $this->category_id, '$this->content', $this->price, $this->weight, $this->tax_id, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
            ) or die('<h4>Inserting item...</h4>' . $this->mysqli->error);

            $this->id = $this->mysqli->insert_id;
        }
        else
        {
            echo '<p style="color: red;">O produto ' . $this->title . ' - ' . $this->reference . ' já existe na base de dados!</p>';
        }
    }
}