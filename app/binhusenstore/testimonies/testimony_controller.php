<?php
require_once(__DIR__ . '/testimony_model.php.php');

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
        $id_product = $req->data->id_product;
        $rating = $req->data->rating;
        $content = $req->data->content;

        $result = null;

        $is_request_body_not_oke = is_null($id_user)
                                    || is_null($id_product)
                                    || is_null($rating)
                                    || is_null($content);

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add testimony, check the data you sent'
                ), 400
            );
            return;
        }

        $result = $this->Binhusenstore_testimony->append_testimony($id_user, $id_product, $rating, $content);

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
        $id_product = $req->data->id_product;

        $result = $this->Binhusenstore_testimony->get_testimonies($id_product);
                
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
            "message" => "testimony not found"
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
                    'message' => 'testimony not found'
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
                    'message' => 'testimony not found'
                ), 404
            );
        }
    }

    public function update_testimony_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $id_user = $req->data->id_user;
        $id_product = $req->data->id_product;
        $rating = $req->data->rating;
        $content = $req->data->content;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $id_user
        $valid_id_user = !is_null($id_user);
        if ($valid_id_user) {
            $keyValueToUpdate["id_user"] = $id_user;
        }

        // conditional $id_product
        $valid_id_product = !is_null($id_product);
        if ($valid_id_product) {
            $keyValueToUpdate["id_product"] = $id_product;
        }

        // conditional $rating
        $valid_rating = !is_null($rating);
        if ($valid_rating) {
            $keyValueToUpdate["rating"] = $rating;
        }

        // conditional $content
        $valid_content = !is_null($content);
        if ($valid_content) {
            $keyValueToUpdate["content"] = $content;
        }

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
                        'message' => 'testimony not found'
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
