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
            'is_paid' => $is_paid,
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

    public function mark_payment_as_paid_by_id($id_order, $date_paid, $balance)
    {
        

        $query_payment_by_id_order = "SELECT id, balance FROM $this->table WHERE id_order = $id_order AND is_paid = 0 ORDER BY date_payment)";
        $retrieve_all_payment = $this->database->sqlQuery($query_payment_by_id_order)->fetchColumn(PDO::FETCH_ASSOC);

        if(count($retrieve_all_payment) === 0) {
            
            return 0;
        }
        
        $balance_left = $balance;

        foreach ($retrieve_all_payment as $value) {
            $payment_id = $value['id'];
            $payment_balance = $value['balance'];

            if($balance_left === 0) continue;
            
            $more_than_bill= $balance_left > $payment_balance;
            // 1000 - 900 = +100;
            
            if($more_than_bill) {
                
                $data_to_update = array(
                    'date_paid' => date("Y/m/d"),
                    'is_paid' => true
                );

                $this->update_payment_by_id($data_to_update, 'id', $payment_id);
            
                $balance_left = $balance_left - $payment_balance; // 1000 - 900= +100
            }

            else {

                $data_to_update = array(
                    'balance' => $payment_balance + $balance_left,
                    'is_paid' => true,
                    'date_paid' => date("Y/m/d")
                );

                $this->update_payment_by_id($data_to_update, 'id', $payment_id);

                $balance_left = 0;
            }
         }
        
    }
}
