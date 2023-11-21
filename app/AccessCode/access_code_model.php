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
        $table_name = $this->table_name;
        $access_code_query = "SELECT * FROM $table_name WHERE source_name = '$source_name' AND code = '$code'";
        $retrieve_data = $this->database->sqlQuery($access_code_query)->fetchAll(PDO::FETCH_ASSOC);
        $is_code_matched = count($retrieve_data) > 0;
        
        if($is_code_matched) return true;
        
        return "Access code or resource name invalid";
        
    }
}