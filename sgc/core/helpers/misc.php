<?php
    /**
     *  FormManager class helper
     */
    function entity(MySQLi $mysqli, $table){
        return new FormManager($mysqli, $table);
    }

    /**
     *  DataValidator class helper
     */
    function validate(){
        return new DataValidator();
    }
