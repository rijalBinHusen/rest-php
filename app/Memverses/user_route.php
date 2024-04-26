<?php
require_once(__DIR__ ."/../Users/user_controller.php");

// register
Flight::route('POST /memverses/user/register', function () {
    $user = new User("memverses_users");
    $user->register();
});

// login
Flight::route('POST /memverses/user/login', function () {
    $user = new User("memverses_users");
    $user->login();
});

// validate
Flight::route('POST /memverses/user/validate', function () {
    $user = new User("memverses_users");
    $user->check_token();
});

// update password
Flight::route('PUT /memverses/user/update_password', function () {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info();

    if ($user_info) {

        $user->update_password_by_id($user_info->data->id);
    }
});

// // validate
// Flight::route('GET /memverses/user', function () {
    
//     echo "Hello world";
// });