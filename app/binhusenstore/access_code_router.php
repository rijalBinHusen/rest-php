<?php
require_once(__DIR__ . '/../AccessCode/access_code_controller.php');
require_once(__DIR__ . '/../Users/user_controller.php');

Flight::route('POST /binhusenstore/access_code', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $access_code = new Access_code();
        $access_code->create_access_code_by_source_name("binhusenstore");
    }
});

Flight::route('GET /binhusenstore/access_code', function () {
    $access_code = new Access_code();
    $access_code->validate_code_on_header("binhusenstore", true);
});