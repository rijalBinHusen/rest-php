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

        if(is_null($email) || is_null($password) || is_null($name)) {
            Flight::json([
                "success" => false,
                "message" => "Unprocessable Entity"
            ], 422);
            return;
        }
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
            ], 422);
        }
    }
    public function check_token () {
        if(isset($_SERVER['HTTP_JWT_AUTHORIZATION'])) {
            $jwt_token = $_SERVER['HTTP_JWT_AUTHORIZATION'];
            $is_token_valid = $this->user->validate($jwt_token);
            
            if($is_token_valid) {
                return true;
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
}
