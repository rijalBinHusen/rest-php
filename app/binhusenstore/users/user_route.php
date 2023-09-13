<?php
require_once(__DIR__ ."/user_controller.php");

// register
Flight::route('POST /binhusenstore/user/register', function () {
    $user = new Binhusenstore_user_controller();
    $user->register();
});

// login
Flight::route('POST /binhusenstore/user/login', function () {
    $user = new Binhusenstore_user_controller();
    $user->login();
});

// validate
Flight::route('POST /binhusenstore/user/validate', function () {
    $user = new Binhusenstore_user_controller();
    $user->check_token();
});