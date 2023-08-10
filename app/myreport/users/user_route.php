<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/user_controller.php");

Flight::route('GET /myreport/users', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_user = new My_report_user();
    $myreport_user->get_users();
});


Flight::route('POST /myreport/user', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) { return; }

    $myreport_user = new My_report_user();
    $myreport_user->add_user();    
});

Flight::route("GET /myreport/user/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) { return; }

    $myreport_user = new My_report_user();
    $myreport_user->get_user_by_id($id);    
});

Flight::route("PUT /myreport/user/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) { return; }

    $myreport_user = new My_report_user();
    $myreport_user->update_user_by_id($id);
});