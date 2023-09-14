<?php
require_once(__DIR__ ."/../Users/user_controller.php");

// register
Flight::route('POST /note_app/user/register', function () {
    $user = new User("note_app_users");
    $user->register();
});

// login
Flight::route('POST /note_app/user/login', function () {
    $user = new User("note_app_users");
    $user->login();
});

// validate
Flight::route('POST /note_app/user/validate', function () {
    $user = new User("note_app_users");
    $user->check_token();
});

// update password
Flight::route('PUT /note_app/user/update_password', function () {
    $user = new User("note_app_users");
    $user_info = $user->get_user_info();

    if ($user_info) {

        $user->update_password($user_info['id']);
    }
});

// // validate
// Flight::route('GET /note_app/user', function () {
    
//     echo "Hello world";
// });