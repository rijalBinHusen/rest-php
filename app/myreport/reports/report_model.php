<?php
require_once(__DIR__ . '/../../../utils/database.php');

class My_report_base_item_model
{
    protected $database;
    var $is_success = true;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
    }

    public function weekly_report($supervisor_id, $head_supervisor_id, $periode1, $periode2)
    {
        // get documents
        // supervisor_id VARCHAR(30),
        // head_spv_id VARCHAR(30),
        $query = "SELECT * FROM $this->table WHERE periode BETWEEN $periode1 AND $periode2";
        $result  = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        else {

            return $result;

        }
        // get komplain
        // retrieve problem
        // retrieve field problem
        // retrieve case



        // $query = "SELECT * FROM $this->table ORDER BY id DESC LIMIT $limit";
        // $result  = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        // if($this->database->is_error !== null) {
        //     $this->is_success = $this->database->is_error;
        // }
        // else {
        //     return $result;
        // }
    }
}
