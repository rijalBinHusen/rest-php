<?php

require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/generator_id.php');

Class SummaryDatabase {
    private static $instance;
    private static $table_name = null;
    private static $database = null;
    public static $summary_database = array();
    public $is_update_summary = false;
    
    public function __construct ($table) {

        self::$summary_database = self::getData($table);
        self::$table_name = $table;

    }

    public static function getInstance($table) {
        if(self::$instance === null) {
            self::$database = Query_builder::getInstance();
            self::$instance = new static($table);
        }

        return self::$instance;
    }

    public static function getData($table) {
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
        if(empty($retrieveData)) {
            
            self::$summary_database[$table] = array (
                'total' => 0,
                'last_id' => 0
            );

        } else {

            foreach($retrieveData as $row) {

                self::$summary_database[$row['table_name']] = array(
                    'total' => $row['total'],
                    'last_id' => $row['last_id']
                );

            }

        }
        
        return self::$summary_database;
    }

    public function getLastId() {
        // find last in global state
        $findLastId = self::$summary_database[self::$table_name]['last_id'];

        // if doesnt exists creat new one
        // nextId
        $lastId = $findLastId ? $findLastId : generateId(self::$table_name ."_22320000");
        
        return $lastId;
    }


    public function getNextId() {

        $findLastId = null;

        $isExists = $this->is_table_name_exists();

        if($isExists) {

            $findLastId = self::$summary_database[self::$table_name]['last_id'];

        }

        // nextId
        $nextId = $findLastId ? generateId($findLastId) : generateId(self::$table_name ."_22320000");


        $nextId = generateId($nextId);
        
        $this->updateLastId($nextId);
    }

    public function updateLastId($your_last_id) {

        $isExists = $this->is_table_name_exists();
        
        $total_record = 0;
        $last_id_record = null;

        if($isExists) {

            $total_record = self::$summary_database[self::$table_name]['total'];
            $last_id_record = self::$summary_database[self::$table_name]['last_id'];

        }
        
        // total record


        $all_last_id = array($your_last_id, $last_id_record);

        $what_last_id_to_set = max($all_last_id);
        
        // set last id in global state
        self::$summary_database[self::$table_name] = array(
            'total' => $total_record + 1,
            'last_id' => $what_last_id_to_set
        );

        if($what_last_id_to_set != $last_id_record) {
            $this->is_update_summary = true;
        }

        return self::$summary_database;
        
    }

    public function is_table_name_exists() {
        $is_exists = array_key_exists(self::$table_name, self::$summary_database);

        return $is_exists;
    }

    public function __destruct()
    {
        $is_summary_no_need_to_update = !$this->is_update_summary;
        if($is_summary_no_need_to_update) {
            return;
        }

        $isExists = $this->is_table_name_exists();
        
        $total_record = 0;
        $last_id_record = null;

        if($isExists) {

            $total_record = self::$summary_database[self::$table_name]['total'];
            $last_id_record = self::$summary_database[self::$table_name]['last_id'];

        }

        if($isExists) {
            $data_to_update = array(
                'total' => $total_record,
                'last_id' => $last_id_record
            );

            self::$database->update('summary', $data_to_update, 'table_name', self::$table_name);


        } else {
        
            $data_to_insert = array(
                'table_name' => self::$table_name,
                'total' => $total_record,
                'last_id' => $last_id_record
            );
            
            self::$database->insert('summary', $data_to_insert);
        }
        
    }
}