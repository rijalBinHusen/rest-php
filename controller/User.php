<?php
require_once(__DIR__ . '/../model/User_model.php');

class User
{   
    protected $user;

    function __construct()
    {
        $this->user = new User_model();
    }
    public function register() {
        $req = Flight::request();
        $email = $req->data->email;
        $password = $req->data->password;
        $name = $req->data->name;

        $this->user->save($name, $email, $password);
    }
}
