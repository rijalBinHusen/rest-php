<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_cart_model
{
    protected $database;
    var $table = "binhusenstore_carts";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_cart($id_user, $product_id, $qty)
    {

        $data_to_insert = array(
            'id_user' => $id_user,
            'product_id' => $product_id,
            'qty' => $qty
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $this->database->lastInsertId();
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_carts($id_user)
    {
        $result  = $this->database->select_where($this->table, 'id_user', $id_user)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_cart_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_cart_by_id(array $data, $where, $id)
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

    public function remove_cart_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }
}
