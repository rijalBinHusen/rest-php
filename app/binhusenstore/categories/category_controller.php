<?php
require_once(__DIR__ . '/category_model.php.php');

class Binhusenstore_category
{
    protected $Binhusenstore_category;
    function __construct()
    {
        $this->Binhusenstore_category = new Binhusenstore_category_model();
    }
    
    public function add_category()
    {
        // request
        $req = Flight::request();
        $name_category = $req->data->name_category;

        $result = null;

        $is_request_body_not_oke = is_null($name_category);

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add category, check the data you sent'
                ), 400
            );
            return;
        }

        $result = $this->Binhusenstore_category->append_category($name_category);

        if($this->Binhusenstore_category->is_success === true) {
        
            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ), 201
            );
        } 
        
        else {
            
            Flight::json(
                array(
                    'success'=> false,
                    'message'=> $this->Binhusenstore_category->is_success
                ), 500
            );
        }
    }
    
    public function get_categories()
    {

        $result = $this->Binhusenstore_category->get_categories();
                
        $is_exists = count($result) > 0;

        if($this->Binhusenstore_category->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->Binhusenstore_category->is_success !== true) {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {
            Flight::json( array(
            "success" => false,
            "message" => "category not found"
            ), 404);
        }

    }
    
    public function get_category_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_category->get_category_by_id($id);

        $is_success = $this->Binhusenstore_category->is_success;

        $is_found = count($result) > 0;

        if($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'category not found'
                ), 404
            );
        }
    }

    public function remove_category($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_category->remove_category_by_id($id);

        $is_success = $this->Binhusenstore_category->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete category success',
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Category not found'
                ), 404
            );
        }
    }

    public function update_category_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $name_category = $req->data->name_category;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $name_category
        $valid_name_category = !is_null($name_category);
        if ($valid_name_category) {
            $keyValueToUpdate["name_category"] = $name_category;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->Binhusenstore_category->update_category_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->Binhusenstore_category->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update category success',
                    )
                );
            }
    
            else if($is_success !== true) {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ), 500
                );
                return;
            }
    
            else {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'Category not found'
                    ), 404
                );
            }
        } 
        
        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update Category, check the data you sent'
                )
            );
        }
    }
}
