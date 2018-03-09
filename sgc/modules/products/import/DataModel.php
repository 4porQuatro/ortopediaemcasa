<?php

require_once('DataModelInterface.php');


abstract class DataModel implements DataModelInterface
{
    public $id;
    public $title;
    public $language_id;
    public $mysqli;
}