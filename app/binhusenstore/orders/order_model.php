<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_order_model
{
    protected $database;
    var $table = "binhusenstore_orders";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_order($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance)
    {

        $data_to_insert = array(
            'date_order' => $date_order,
            'id_group' => $id_group,
            'is_group' => (int)$is_group,
            'id_product' => $id_product,
            'name_of_customer' => $name_of_customer,
            'sent' => $sent,
            'title' => $title,
            'total_balance' => $total_balance,
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $this->database->getMaxId($this->table);
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_orders()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_order_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_order_by_id(array $data, $where, $id)
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

    public function remove_order_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }

    public function count_orders()
    {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->database->sqlQuery($query)->fetchColumn();

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }
}
