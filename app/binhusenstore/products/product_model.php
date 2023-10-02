<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_product_model
{
    protected $database;
    var $table = "binhusenstore_products";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_product($name, $categories, $price, $weight, $images, $description, $default_total_week, $is_available)
    {

        $data_to_insert = array(
            'name' => $name,
            'categories' => $categories,
            'price' => $price,
            'weight' => $weight,
            'images' => $images,
            'description' => $description,
            'default_total_week' => $default_total_week,
            'is_available' => $is_available
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $this->database->getMaxId($this->table);
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function get_products($limit = 0)
    {
        
        $query = "SELECT * FROM $this->table ORDER BY id DESC";

        if($limit > 0) {
            $query = $query . " LIMIT 30";
        }
        $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_product_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_product_by_id(array $data, $where, $id)
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

    public function remove_product_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }

    public function get_products_landing_page () {
        
        // get categories first
        $categories  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        $is_categories_exists = count($categories) > 0;

        if(!$is_categories_exists) { return array(); };

        $result = array();
        // get products where category = cat, limit 4
        $table_product = $this->table;
        foreach ($categories as $value) {
            $category_id = $value['id'];
            $query_product = "SELECT * FROM $table_product WHERE MATCH(categories) AGAINST ('$category_id' IN NATURAL LANGUAGE MODE) ORDER BY id DESC LIMIT 4";
            $get_products = $this->database->sqlQuery($query_product)->fetchAll(PDO::FETCH_ASSOC);
            
            $is_product_exists = count($get_products) > 0;

            if($is_product_exists) {
                
                $array_to_push = array(
                    "category" => $value['name'],
                    "products" => $get_products
                );

                array_push($result, $array_to_push);
            }
        }

        return $result;

    }
}
