<?php
require_once(__DIR__ ."/user_controller.php");

// register
Flight::route('POST /note_app/user/register', function () {
    $user = new Note_app_user_controller();
    $user->register();
});

// login
Flight::route('POST /note_app/user/login', function () {
    $user = new Note_app_user_controller();
    $user->login();
});

// validate
Flight::route('POST /note_app/user/validate', function () {
    $user = new Note_app_user_controller();
    $user->check_token();
});