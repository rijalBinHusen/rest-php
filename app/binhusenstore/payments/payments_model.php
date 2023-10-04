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

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $this->database->getMaxId($this->table);
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

    public function mark_payment_as_paid_by_id($id_payment, $date_paid, $balance)
    {

        $retrieve_payment = $this->get_payment_by_id($id_payment);

        if(count($retrieve_payment) === 0) {

            return 0;
        }

        $date_payment_db = $retrieve_payment[0]['date_payment'];
        $id_order_db =  $retrieve_payment[0]['id_order'];
        $balance_db =  $retrieve_payment[0]['balance'];

        $balance_variance = $balance_db - $balance;

        $data_to_update = array(
            'date_paid' => $date_paid,
            'balance' => $balance,
            'is_paid' => true,
        );

        // less than db balance
        if($balance_variance > 0) {

            // create new payment with balance variance
            $this->append_payment($date_payment_db, $id_order_db, $balance_variance, false);
        }

        else if($balance_variance < 0) {

            $query = "SELECT id, balance FROM $this->table WHERE id_order = $id_order_db ORDER BY date_payment LIMIT 1)";
            $retrieve_next_payment = $this->database->sqlQuery($query)->fetchColumn(PDO::FETCH_ASSOC);

            $id_next_payment_db =  $retrieve_next_payment[0]['id'];
            $balance_next_payment_db =  $retrieve_next_payment[0]['balance'];
            $balance_after_decrease = $balance_next_payment_db + $balance_variance;

            $next_payment_date_to_update = array(
                'balance' => $balance_after_decrease
            );

            // decrease balance by variance
            $this->update_payment_by_id($next_payment_date_to_update, 'id', $id_next_payment_db);
        }


        $result = $this->update_payment_by_id($data_to_update, 'id', $id_payment);

        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }
}
