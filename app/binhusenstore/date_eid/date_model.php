<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_date_model
{
    protected $database;
    var $table = "binhusenstore_date_eid";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_date($year, $date_eid)
    {

        $data_to_insert = array(
            'year' => $year,
            'date' => $date_eid
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $year;
        }
            
        $this->is_success = $this->database->is_error;

    }

    public function get_dates()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function update_date_by_year($data_to_update, $year)
    {

        $result = $this->database->update($this->table, $data_to_update, 'year', $year);

        if($this->database->is_error === null) {
            
            return $result;
        } 

        $this->is_success = $this->database->is_error;

    }

    public function remove_date_by_year($year)
    {
        $result = $this->database->delete($this->table, 'year', $year);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }
}
