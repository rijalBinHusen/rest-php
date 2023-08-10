<?php
require_once(__DIR__ . '/user_model.php');

class My_report_user
{
    protected $my_report_user;
    function __construct()
    {
        $this->my_report_user = new My_report_user_model();
    }
    public function get_users()
    { 

        $result = $this->my_report_user->get_users();

        $is_found = count($result) > 0;

        $is_success = $this->my_report_user->is_success;
        
        if($is_success === true && $is_found) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }
        
        else if($is_success !== true) {
            Flight::json( array(
                "success" => false,
                "message" => $result
                )
            , 500);
        }
        
        else {
            Flight::json(array(
                "success" => false,
                "message" => "Users not found"
                )
            , 404);
        }

    }
    public function add_user()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $username = $req->data->username;
        $password = $req->data->password;

        $valid_request_body = !is_null($username)
                                && !is_null($password);

        $result = null;

        if($valid_request_body) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_user->write_user($id, $username, $password);
            } else {
                // append warehouse
                $result = $this->my_report_user->append_user($username, $password);
            }

            if($this->my_report_user->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_user->is_success
                    ), 500
                );
            }

            else {
                
                Flight::json(
                    array(
                        'success' => true,
                        'id' => $result
                    ), 201
                );

            }
        }

        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add user, check the data you sent'
                ), 400
            );
            
        }
    }
    public function get_user_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_user->get_user_by_id($id);

        $is_success = $this->my_report_user->is_success;

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
                    'message' => 'User not found'
                ), 404
            );
        }
    }

    public function update_user_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $username = $req->data->username;
        $password = $req->data->password;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional username
        $valid_username = !is_null($username) && !empty($username);
        if ($valid_username) {
            $keyValueToUpdate["username"] = $username;
        }

        // conditional $password
        $valid_password = !is_null($password) && !empty($password);
        if ($valid_password) {
            $keyValueToUpdate["password"] = $password;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_user->update_user_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_user->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update user success',
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
                        'message' => 'User not found'
                    ), 404
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update user, check the data you sent'
                ), 400
            );
        }

        
    }
}
