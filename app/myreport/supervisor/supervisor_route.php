<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/supervisor_controller.php");

Flight::route('GET /myreport/supervisors', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_supervisor = new My_report_supervisor();
    $myreport_supervisor->get_supervisors();
});


Flight::route('POST /myreport/supervisor', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }
    $myreport_supervisor = new My_report_supervisor();
    $myreport_supervisor->add_supervisor();    
});

Flight::route("GET /myreport/supervisor/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_supervisor = new My_report_supervisor();
    $myreport_supervisor->get_supervisor_by_id($id);    
});

Flight::route("PUT /myreport/supervisor/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_supervisor = new My_report_supervisor();
    $myreport_supervisor->update_supervisor_by_id($id);
});