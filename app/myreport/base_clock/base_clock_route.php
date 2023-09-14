<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/base_clock_controller.php");

Flight::route('POST /myreport/base_clock', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_clock = new My_report_base_clock();
        $myreport_base_clock->add_base_clock();    
    }
});

Flight::route('GET /myreport/base_clocks', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_clock = new My_report_base_clock();
        $myreport_base_clock->get_base_clock_by_parent();
    }
});

Flight::route('DELETE /myreport/base_clocks', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_clock = new My_report_base_clock();
        $myreport_base_clock->remove_base_clock_by_parent();
    }
});

Flight::route("GET /myreport/base_clock/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_clock = new My_report_base_clock();
        $myreport_base_clock->get_base_clock_by_id($id);    
    }
});

Flight::route("PUT /myreport/base_clock/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_clock = new My_report_base_clock();
        $myreport_base_clock->update_base_clock_by_id($id);
    }
});

Flight::route("DELETE /myreport/base_clock/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_clock = new My_report_base_clock();
        $myreport_base_clock->remove_base_clock($id);
    }
});