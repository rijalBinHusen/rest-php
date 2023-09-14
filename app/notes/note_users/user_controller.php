<?php
require_once(__DIR__ . '/user_model.php');

class Note_app_user_controller
{   
    protected $user;

    function __construct()
    {
        $this->user = new Note_app_user_model();
    }
    public function login()
    {
        $req = Flight::request();
        $email = $req->data->email;
        $password = $req->data->password;

        $token = $this->user->login($email, $password);
        $errorLogin = $this->user->error;

        if(is_null($errorLogin)) {
            Flight::json([
                'success' => true,
                'token' => $token,
            ], 200);
        } 
        
        else {
            Flight::json([
                'success' => false,
                'message' => $errorLogin
            ], 401);
        }
    }
    public function register() {
        $req = Flight::request();
        $email = $req->data->email;
        $password = $req->data->password;
        $name = $req->data->name;

        $invalid_request_body = is_null($email) || is_null($password) || is_null($name) || empty($email) || empty($password) || empty($name);

        if($invalid_request_body) {
            Flight::json([
                "success" => false,
                "message" => "Unprocessable Entity"
            ], 422);
        } 
        else {

            $this->user->save($name, $email, $password);
            $errorLogin = $this->user->error;
            
            if(is_null($errorLogin)) {
                Flight::json([
                    'success' => true,
                    'message' => 'Registration success.',
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'message' => $errorLogin,
                ], 409);
            }
        }
    }
    public function check_token () {
        if(isset($_SERVER['HTTP_JWT_AUTHORIZATION'])) {
            $jwt_token = $_SERVER['HTTP_JWT_AUTHORIZATION'];
            $is_token_valid = $this->user->validate($jwt_token);
            
            if($is_token_valid) {
                Flight::json([
                    'success' => true,
                    'message' => 'Valid token',
                ], 200);
            } 
            
            else {
                Flight::json([
                    'success' => false,
                    'message' => 'Invalid token',
                ], 401);
            }

        } else {
            Flight::json([
                'success' => false,
                'message' => 'You must be authenticated to access this resource.',
            ], 401);
        }
        
    }
    public function get_user_info() {
        if(isset($_SERVER['HTTP_JWT_AUTHORIZATION'])) {

            $jwt_token = $_SERVER['HTTP_JWT_AUTHORIZATION'];
            $user_info_by_jwt = $this->user->validate($jwt_token);
            if($user_info_by_jwt) {

                return $user_info_by_jwt;
            } else {
                
                Flight::json([
                    'success' => false,
                    'message' => 'Invalid token',
                ], 401);
                
            }
            
        } else {

            Flight::json([
                'success' => false,
                'message' => 'You must be authenticated to access this resource.',
            ], 401);
        }

        return false;
    }

    
    public function update_password($id_user) {

        $req = Flight::request();
        $password = $req->data->password;

        $invalid_request_body = is_null($password)|| empty($password);

        if($invalid_request_body) {

            Flight::json([
                "success" => false,
                "message" => "Password can't be null or empty"
            ], 400);
        } 
        else {

            $row_updated = $this->user->save(null, null, $password, $id_user);
            $errorUpdateUser = $this->user->error;
            
            if(is_null($errorUpdateUser) && $row_updated > 0) {

                Flight::json([
                    'success' => true,
                    'message' => 'Update password success.',
                    'row_updated' => $row_updated,
                    'errorupdateuser' => $errorUpdateUser,
                    'id_user' => $id_user
                ]);
            } 
            
            else {

                Flight::json([
                    'success' => false,
                    'message' => $errorUpdateUser,
                ], 500);
            }
        }
    }
}
