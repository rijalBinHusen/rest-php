<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_category_model
{
    protected $database;
    var $table = "binhusenstore_categories";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_category($name_category)
    {

        $data_to_insert = array(
            'name_category' => $name_category,
            'is_landing_page' => 0
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $this->database->getMaxId($this->table);
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_categories()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            $convert_data_type = $this->convert_data_type($result);
            return $convert_data_type;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_category_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            $convert_data_type = $this->convert_data_type($result);
            return $convert_data_type;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_category_by_id(array $data, $where, $id)
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

    public function remove_category_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }

    private function convert_data_type($categories)
    {
        $result = array();

        // mapping products
        foreach ($categories as $category_value) {
            array_push($result, array(
                "id" => $category_value['id'],
                "name_category" => $category_value['name_category'],
                "is_landing_page" => boolval($category_value['is_landing_page'])
            ));
        }

        return $result;
    }
}
