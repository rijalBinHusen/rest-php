<?php

require_once(__DIR__ . "/../../utils/database.php");

Class Access_code_model {
    private $database = null;
    public $error = null;
    private $table_name = "access_code";
    function __construct()
    {
        $this->database = Query_builder::getInstance();
    }

    function create_code($source_name, $code) {
        // delete row first
        $this->database->delete($this->table_name, 'source_name', $source_name);
        // insert new one
        $data_to_insert = array(
            'source_name' => $source_name,
            'code' => $code,
        );

        $this->database->insert($this->table_name, $data_to_insert);

        if($this->database->is_error !== null) {

            return $this->database->is_error;

        }

        return true;
    }

    function validate_code($source_name, $code) {
        $find_source = $this->database->select_where($this->table_name, 'source_name', $source_name)->fetch();
        
        // is soucre find
        if(is_array($find_source)) {
            // is code matched
            $is_code_matched = $code == $find_source['code'];
            
            if($is_code_matched) {
                return true;
            }

            return "Access code invalid";
        }

        return "Source not found";
    }
}