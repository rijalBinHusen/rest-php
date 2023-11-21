<?php
require_once(__DIR__ . '/access_code_model.php');


class Access_code {
    protected $access_code;
    function __construct()
    {
        $this->access_code = new Access_code_model();
    }

    function create_access_code() {
        $req = Flight::request();
        $source_name = $req->data->source_name;
        $code = $req->data->code;

        $valid_request_body = !is_null($source_name) 
                                && !empty($source_name)
                                && !is_null($code)
                                && !empty($code);

        if($valid_request_body) {
            
            $result = $this->access_code->create_code($source_name, $code);

            if($result !== true) {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $result
                    ), 500
                );

            } else {

                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Your code is set'
                    ), 201
                );

            }

        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => "Request body invalid"
                ), 400
            );

        }

    }

    function validate_code () {
        $req = Flight::request();
        $source_name = $req->data->source_name;
        $code = $req->data->code;

        $valid_request_body = !is_null($source_name) 
                                && !empty($source_name)
                                && !is_null($code)
                                && !empty($code);

        if($valid_request_body) {
            
            $result = $this->access_code->validate_code($source_name, $code);

            if($result !== true) {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $result
                    ), 401
                );

            } else {

                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Your code is valid'
                    )
                );

            }

        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => "Request body invalid"
                ), 400
            );

        }

    }

    function validate_code_on_header ($source_name) {
        $code = null;
        if(isset($_SERVER['HTTP_CODE_AUTHORIZATION'])) {

            $code = $_SERVER['HTTP_CODE_AUTHORIZATION'];
        }

        $valid_request_body = !is_null($source_name) 
                                && !empty($source_name)
                                && !is_null($code)
                                && !empty($code);

        if($valid_request_body) {
            
            $result = $this->access_code->validate_code($source_name, $code);

            if($result !== true) {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $result
                    ), 401
                );

            } else return true;

        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => "You must be authenticated to access this resource."
                ), 401
            );

        }

    }
}