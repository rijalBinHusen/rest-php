<?php
require_once(__DIR__ . '/user_model.php');

class User
{   
    protected $user;

    function __construct()
    {
        $this->user = new User_model();
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
                'message' => $errorLogin,
            ], 404);
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
    public function is_valid_token() {
        if(isset($_SERVER['HTTP_JWT_AUTHORIZATION'])) {

            $jwt_token = $_SERVER['HTTP_JWT_AUTHORIZATION'];
            $is_token_valid = $this->user->validate($jwt_token);
            return $is_token_valid;
            
        } else {
            return false;
        }
    }
}
