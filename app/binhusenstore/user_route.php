<?php
require_once(__DIR__ ."/../Users/user_controller.php");

// register
Flight::route('POST /binhusenstore/user/register', function () {
    $user = new User("binhusenstore_users");
    $user->register();
});

// login
Flight::route('POST /binhusenstore/user/login', function () {
    $user = new User("binhusenstore_users");
    $user->login();
});

// validate
Flight::route('POST /binhusenstore/user/validate', function () {
    $user = new User("binhusenstore_users");
    $user->check_token();
});

// update password
Flight::route('PUT /binhusenstore/user/update_password', function () {
    $user = new User("binhusenstore_users");
    $user_info = $user->get_user_info();

    if ($user_info) {

        $user->update_password_by_id($user_info->data->id);
    }
});

// // validate
// Flight::route('GET /binhusenstore/user', function () {
    
//     echo "Hello world";
// });