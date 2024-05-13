<?php

require_once(__DIR__ . "/../../utils/database.php");

class Access_code_model
{
    private $database = null;
    public $is_success = true;
    private $table_name = "access_code";
    function __construct()
    {
        $this->database = Query_builder::getInstance();
    }

    function create_code($source_name, $code)
    {
        // delete row first
        $this->database->delete($this->table_name, 'source_name', $source_name);
        // insert new one
        $data_to_insert = array(
            'source_name' => $source_name,
            'code' => $code,
        );

        $this->database->insert($this->table_name, $data_to_insert);

        if ($this->database->is_error !== null) {

            return $this->database->is_error;
        }

        return true;
    }

    function validate_code($source_name, $code)
    {
        $table_name = $this->table_name;
        $where_s = array(
            'source_name' => $source_name,
            'code' => $code
        );
        $retrieve_data = $this->database->select_where_s($table_name, $where_s)->fetchAll(PDO::FETCH_ASSOC);
        $is_code_matched = count($retrieve_data) > 0;

        if ($is_code_matched) return true;

        return "Access code or resource name invalid";
    }

    public function retrieve_access_code_by_source_name($source_name)
    {

        $result = $this->database->select_where($this->table_name, 'source_name', $source_name)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }
}
