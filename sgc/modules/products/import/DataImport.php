<?php

class DataImport
{
    private $files;

    public function __construct($files = null)
    {
        $this->files = [];

        if(!empty($files))
        {
            $this->setFiles($files);
        }
    }

    public function setFiles($files)
    {
        if(is_array($files))
        {
            $this->files = array_merge($this->files, $files);
        }
        else
        {
            $this->files[] = $files;
        }
    }

    public function getFiles()
    {
        return $this->files;
    }


    public static function rowIsEmpty($columns)
    {
        foreach ($columns as $column) {
            if (!empty($column)) {
                return false;
            }
        }

        return true;
    }

    public static function rowHasCategory($columns)
    {
        return !empty($columns[0]) && empty($columns[1]) && empty($columns[2]);
    }

    public static function getCategoriesTree($string)
    {
        $tree = explode('>', $string);

        return array_map('trim', $tree);
    }
}