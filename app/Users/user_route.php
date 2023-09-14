<?php
require_once(__DIR__ ."/user_controller.php");

// register
Flight::route('POST /user/register', function () {

    $user = new User("users");
    $user->register();
});

// login
Flight::route('POST /user/login', function () {

    $user = new User("users");
    $user->login();
});

// validate
Flight::route('POST /user/validate', function () {
    
    $user = new User("users");
    $user->check_token();
});