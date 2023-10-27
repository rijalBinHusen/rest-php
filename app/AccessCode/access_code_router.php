<?php
require_once(__DIR__ . '/access_code_controller.php');
require_once(__DIR__ . '/../Users/user_controller.php');

Flight::route('POST /access_code/create', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $access_code = new Access_code();
        $access_code->create_access_code();
    }
});

Flight::route('POST /access_code/validate', function () {
    $access_code = new Access_code();
    $access_code->validate_code();
});