<?php
require_once(__DIR__ . '/../model/User_model.php');

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
            ]);
        } else {
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

        $this->user->save($name, $email, $password);
    }
}