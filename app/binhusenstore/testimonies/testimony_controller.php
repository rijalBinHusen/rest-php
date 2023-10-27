<?php
require_once(__DIR__ . '/testimony_model.php');

class Binhusenstore_testimony
{
    protected $Binhusenstore_testimony;
    function __construct()
    {
        $this->Binhusenstore_testimony = new Binhusenstore_testimony_model();
    }
    
    public function add_testimony()
    {
        // request
        $req = Flight::request();
        $id_user = $req->data->id_user;
        $display_name = $req->data->display_name;
        $id_product = $req->data->id_product;
        $rating = $req->data->rating;
        $content = $req->data->content;

        $result = null;

        $is_request_body_not_oke = is_null($id_user)
                                    || is_null($id_product)
                                    || is_null($display_name)
                                    || is_null($rating)
                                    || !empty($id_user)
                                    || !empty($id_product)
                                    || !empty($display_name)
                                    || is_numeric($rating);
        if(is_null($content) || empty($content)) $content = "Tidak ada review dari pengguna";

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add testimony, check the data you sent'
                ), 400
            );
            return;
        }

        $result = $this->Binhusenstore_testimony->append_testimony($id_user, $id_product, $rating, $content, $display_name);

        if($this->Binhusenstore_testimony->is_success === true) {
        
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
                    'message'=> $this->Binhusenstore_testimony->is_success
                ), 500
            );
        }
    }
    
    public function get_testimonies()
    {
        // catch the query string request
        $req = Flight::request();
        $id_product = $req->query->id_product;
        $limit = $req->query->limit;

        $result = array();

        if(is_null($id_product)) {

            $result = $this->Binhusenstore_testimony->get_testimonies($limit);
        }

        else {
            
            $result = $this->Binhusenstore_testimony->get_testimoniesByIdProduct($id_product);
        }
                
        $is_exists = count($result) > 0;

        if($this->Binhusenstore_testimony->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->Binhusenstore_testimony->is_success !== true) {

            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {

            Flight::json( array(
            "success" => false,
            "message" => "Testimony not found"
            ), 404);
        }

    }
    
    public function get_testimony_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_testimony->get_testimony_by_id($id);

        $is_success = $this->Binhusenstore_testimony->is_success;

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
        }

        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Testimony not found'
                ), 404
            );
        }
    }
    
    public function get_testimony_for_landing_page()
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_testimony->get_testimony_landing_page();

        $is_success = $this->Binhusenstore_testimony->is_success;

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
        }

        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Testimony not found'
                ), 404
            );
        }
    }

    public function remove_testimony($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_testimony->remove_testimony_by_id($id);

        $is_success = $this->Binhusenstore_testimony->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete testimony success',
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
                    'message' => 'Testimony not found'
                ), 404
            );
        }
    }

    public function update_testimony_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $rating = $req->data->rating;
        $content = $req->data->content;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $rating
        $valid_rating = !is_null($rating) && is_numeric($rating);
        if ($valid_rating) $keyValueToUpdate["rating"] = $rating;

        // conditional $content
        $valid_content = !is_null($content) && !empty($content);
        if ($valid_content) $keyValueToUpdate["content"] = $content; 
        else $keyValueToUpdate["content"] = "Tidak ada review dari pengguna";

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->Binhusenstore_testimony->update_testimony_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->Binhusenstore_testimony->is_success;
    
            if($is_success === true && $result > 0) {

                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update testimony success',
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
                        'message' => 'Testimony not found'
                    ), 404
                );
            }
        } 
        
        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update testimony, check the data you sent'
                )
            );
        }
    }
}
