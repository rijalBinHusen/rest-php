<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_testimony_model
{
    protected $database;
    var $table = "binhusenstore_testimonies";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_testimony($id_user, $id_product, $rating, $content)
    {

        $data_to_insert = array(
            'id_user' => $id_user,
            'id_product' => $id_product,
            'rating' => $rating,
            'content' => $content
        );

        $inserted_id = $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $inserted_id;
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_testimonies($id_product)
    {
        $result  = $this->database->select_where($this->table, 'id_product', $id_product)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_testimony_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_testimony_by_id(array $data, $where, $id)
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

    public function remove_testimony_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }
}
