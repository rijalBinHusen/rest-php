<?php

require_once(__DIR__ . './database.php');
require_once(__DIR__ . './generator_id.php');

Class SummaryDatabase {
    private $table = null;
    private $database = null;
    private $total = null;
    private $last_id = null;
    
    public function __construct ($table) {
        $this->database = new Query_builder();
        $this->table = $table;
    }

    public function getNextId() {
        $summary = $this->database->select_where("summary", 'table', $this->table);
        
        $this->total = $summary['total'];
        $this->last_id = $summary['last_id'];
        // nextId
        $nextId = $this->last_id ? generateId($this->last_id) : generateId($this->table ."_22320000");
        $this->last_id = $nextId;
        
        return $nextId;
    }

    public function updateLastId($your_last_id) {
        $what_last_id_to_set = $your_last_id ? $your_last_id : $this->last_id;
        
        $data = array(
            'last_id' => $what_last_id_to_set,
            'total' => $this->total + 1,
        );
        
        $this->database->insert($this->table, $data);
    }
}