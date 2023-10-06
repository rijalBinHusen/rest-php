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

    public function append_payment($date_payment, $id_order, $balance)
    {

        $data_to_insert = array(
            'date_payment' => $date_payment,
            'id_order' => $id_order,
            'balance' => $balance,
            'is_paid' => false,
            'date_paid' => "",
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

    public function mark_payment_as_paid_by_id($id_order, $date_paid, $payment)
    {
        
        $query_payment_by_id_order = "SELECT id, balance, date_payment FROM $this->table WHERE id_order = '$id_order' AND is_paid = '0' ORDER BY date_payment";
        $retrieve_all_payment = $this->database->sqlQuery($query_payment_by_id_order)->fetchAll(PDO::FETCH_ASSOC);

        if(count($retrieve_all_payment) === 0) {
            
            return false;
        }
        
        $payment_left = $payment;

        for ($i = 0; $i < count($retrieve_all_payment); $i++) {
            $payment_id = $retrieve_all_payment[$i]['id'];
            $payment_balance = $retrieve_all_payment[$i]['balance'];
            $payament_date = $retrieve_all_payment[$i]['date_payment'];

            if($payment_left === 0) return true;

            if($payment_left < 0) {

                $is_the_last_iteration = $i + 1 === count($retrieve_all_payment);
                if($is_the_last_iteration) {

                    $this->append_payment($payament_date, $id_order, (- $payment_left), false);
                } else {

                    $data_to_update = array('balance' => $payment_balance + (- $payment_left));
                    $this->update_payment_by_id($data_to_update, 'id', $payment_id);
                }

                return true;
            }
            
            $is_payment_more_than_bill= $payment_left >= $payment_balance;
            // 1000 - 900 = +100;
            
            if($is_payment_more_than_bill) {
                
                $data_to_update = array(
                    'date_paid' => $date_paid,
                    'is_paid' => true
                );

                $this->update_payment_by_id($data_to_update, 'id', $payment_id);
            }
            
            else {
                
                $data_to_update = array(
                    'balance' => $payment_left,
                    'is_paid' => true,
                    'date_paid' => $date_paid
                );
                
                $this->update_payment_by_id($data_to_update, 'id', $payment_id);
            }

            $payment_left = $payment_left - $payment_balance; // 1000 - 900= +100
        }

        return true;

        $this->is_success = $this->database->is_error;
    }
}
