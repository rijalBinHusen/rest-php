<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/head_spv_controller.php");

Flight::route('GET /myreport/supervisors', function () {
    $user = new User();
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_supervisor = new My_report_head_spv();
    $myreport_supervisor->get_heads_spv();
});


Flight::route('POST /myreport/supervisor', function () {
    $user = new User();
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }
    $myreport_supervisor = new My_report_head_spv();
    $myreport_supervisor->add_head_spv();    
});

Flight::route("GET /supervisor/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_supervisor = new My_report_head_spv();
    $myreport_supervisor->get_head_spv_by_id($id);    
});

Flight::route("PUT /supervisor/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_supervisor = new My_report_head_spv();
    $myreport_supervisor->update_head_spv_by_id($id);
});