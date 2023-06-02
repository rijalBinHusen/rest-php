<?php

require_once(__DIR__ . './database.php');
require_once(__DIR__ . './generator_id.php');

Class SummaryDatabase {
    private $table = null;
    private static $database = null;
    private $total = null;
    private $last_id = null;
    private $is_table_exists = false;
    private $table_as_id = null;
    public static $summary_database = array();
    
    public function __construct ($table) {
        $connection_db = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
        $connection_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$database = Query_builder::getInstance($connection_db);

        self::$summary_database = self::getData();
        $this->table = $table;
        $this->table_as_id = str_replace("my_report_", "", $table);
    }

    public static function getData() {
        $retrieveData = self::$database->select_from("summary")->fetchAll(PDO::FETCH_ASSOC);
        // self::$summary_database = $retrieveData;
        /*
          convert array from 
          array(
           (
            [0] => Array
                (
                    [table_name] => my_report_base_clock
                    [total] => 2809
                    [last_id] => base_clock_23222808
                )
        
            [1] => Array
                (
                    [table_name] => my_report_base_item
                    [total] => 2511
                    [last_id] => base_item_23222257
                )

            [2] => Array
                (
                    [table_name] => my_report_base_report_file
                    [total] => 2485
                    [last_id] => base_report_file_23222484
                )

            [3] => Array
                (
                    [table_name] => my_report_base_stock
                    [total] => 2871
                    [last_id] => base_stock_23222870
                )

            into

            array (
                table_name => array (
                    total => 2871,
                    last_id => base_stock_23222870
                )
            )
         */
        foreach($retrieveData as $row) {
            self::$summary_database[$row['table_name']] = array(
                'total' => $row['total'],
                'last_id' => $row['last_id']
            );
        }

        return self::$summary_database;
    }

    public function getLastId() {
        // find last in global state
        $findLastId = self::$summary_database[$this->table]['last_id'];

        // if doesnt exists creat new one
        // nextId
        $lastId = $findLastId ? $findLastId : generateId($this->table_as_id ."_22320000");
        $this->is_table_exists = !empty($findLastId) || !is_null($findLastId);
        
        return $lastId;
    }


    public function getNextId() {
        $findLastId = self::$summary_database[$this->table]['last_id'];
        // nextId
        $nextId = $findLastId ? generateId($findLastId) : generateId($this->table_as_id ."_22320000");
        
        return $nextId;
    }

    public function updateLastId($your_last_id) {

        // total record
        $total_record = self::$summary_database[$this->table]['total'];
        $last_id_record = self::$summary_database[$this->table]['last_id'];
        $all_last_id = array($your_last_id, $last_id_record);

        $what_last_id_to_set = max($all_last_id);
        // set last id in global state
        self::$summary_database[$this->table] = array(
            'total' => $total_record + 1,
            'last_id' => $what_last_id_to_set
        );

        $data = array(
            'table_name' => $this->table,
            'total' => $total_record + 1,
            'last_id' => $what_last_id_to_set
        );
        
        if($this->is_table_exists) {
            self::$database->update('summary', self::$summary_database[$this->table], 'table_name', $this->table);

        } else {
            $array['table_name'] = $this->table;
            self::$database->insert('summary', $data);
        }
        
    }
}