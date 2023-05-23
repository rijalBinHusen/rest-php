<?php

require_once(__DIR__ . './database.php');
require_once(__DIR__ . './generator_id.php');

Class SummaryDatabase {
    private $table = null;
    private $database = null;
    private $total = null;
    private $last_id = null;
    private $is_table_exists = false;
    private $table_as_id = null;
    
    public function __construct ($table) {
        $this->database = new Query_builder();
        $this->table = $table;
        $this->table_as_id = str_replace("my_report_", "", $table);
    }

    public function getLastId() {
        $summary = $this->database->select_where("summary", 'table_name', $this->table)->fetch();
        
        $this->total = $summary['total'];
        $this->last_id = $summary['last_id'];
        // nextId
        $lastId = $this->last_id ? $this->last_id : generateId($this->table_as_id ."_22320000");
        
        return $lastId;
    }


    public function getNextId() {
        $summary = $this->database->select_where("summary", 'table_name', $this->table)->fetch();
        
        $this->total = $summary['total'];
        $this->last_id = $summary['last_id'];
        $this->is_table_exists = !empty($summary['last_id']) || !is_null($summary['last_id']);
        // nextId
        $nextId = $this->last_id ? generateId($this->last_id) : generateId($this->table_as_id ."_22320000");
        $this->last_id = $nextId;
        
        return $nextId;
    }

    public function updateLastId($your_last_id) {
        $is_empty_last_id = is_null($your_last_id) || empty($your_last_id);

        $what_last_id_to_set = $is_empty_last_id ? $this->last_id : $your_last_id;
        
        $data = array(
            'table_name' => $this->table,
            'last_id' => $what_last_id_to_set,
            'total' => $this->total + 1,
        );
        
        if($this->is_table_exists) {
            $this->database->update('summary', $data, 'table_name', $this->table);

        } else {

            $this->database->insert('summary', $data);
        }
        
    }
}