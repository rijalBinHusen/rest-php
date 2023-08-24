<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_payment_model
{
    protected $database;
    var $table = "binhusenstore_payments";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_payment($date_payment, $id_order, $balance, $is_paid)
    {

        $data_to_insert = array(
            'date_payment' => $date_payment,
            'id_order' => $id_order,
            'balance' => $balance,
            'is_paid' => $is_paid
        );

        $inserted_id = $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $inserted_id;
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_payments($id_order)
    {
        $result  = $this->database->select_where($this->table, 'id_order', $id_order)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payment_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_payment_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error === null) {
    
            if($result === 0) {

                $query = "SELECT EXISTS(SELECT id FROM $this->table WHERE id = '$id')";
                return $this->database->sqlQuery($query)->fetchColumn();
            }
            
            return $result;
        } 

        $this->is_success = $this->database->is_error;

    }

    public function remove_payment_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }
}
