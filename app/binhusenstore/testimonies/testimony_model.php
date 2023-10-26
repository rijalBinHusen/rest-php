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

    public function append_testimony($id_user, $id_product, $rating, $content, $display_name)
    {

        $data_to_insert = array(
            'id_user' => $id_user,
            'display_name' => $display_name,
            'id_product' => $id_product,
            'rating' => $rating,
            'content' => $content
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $this->database->getMaxId($this->table);
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_testimonies($limit)
    {
        
        $columnToSelect = "display_name, content, rating";
        $table_testimony = $this->table;
        $query_testimony = "SELECT $columnToSelect FROM $table_testimony ORDER BY id DESC";
        
        if(is_numeric($limit) && $limit > 1) {

            $query_testimony = $query_testimony . " LIMIT $limit";
        } else {
            
            $query_testimony = $query_testimony . " LIMIT 10";
        }

        $result = $this->database->sqlQuery($query_testimony)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null && count($result) > 0) {

            $converted_data_type = $this->convert_data_type_simple_record($result);
            return $converted_data_type;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_testimoniesByIdProduct($id_product)
    {
        $columnToSelect = "display_name, content, rating";
        $table_testimony = $this->table;
        $query_testimony = "SELECT $columnToSelect FROM $table_testimony WHERE 'id_product' =  '$id_product' ORDER BY id DESC";
        $result = $this->database->sqlQuery($query_testimony)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            $converted_data_type = $this->convert_data_type_simple_record($result);
            return $converted_data_type;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_testimony_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {


            $converted_data_type = $this->convert_data_type($result);
            return $converted_data_type;
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

    public function convert_data_type($testimonies) {
        $result = array();

        foreach ($testimonies as $value) {
            array_push($result, array(
                'id' => $value['id'],
                'display_name' => $value['display_name'],
                'id_product' => $value['id_product'],
                'rating' => (int)$value['rating'],
                'content' => $value['content'],
            ));
        }

        return $result;
    }

    public function convert_data_type_simple_record($testimonies) {
        $result = array();

        foreach ($testimonies as $value) {
            array_push($result, array(
                'display_name' => $value['display_name'],
                'rating' => (int)$value['rating'],
                'content' => $value['content'],
            ));
        }

        return $result;
    }
}