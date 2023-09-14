<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/case_controller.php");

Flight::route('GET /myreport/cases', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case = new My_report_case();
        $myreport_case->get_cases();
    }
});


Flight::route('POST /myreport/case', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
    
        $myreport_case = new My_report_case();
        $myreport_case->add_case();    
    }
});

Flight::route("GET /myreport/case/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case = new My_report_case();
        $myreport_case->get_case_by_id($id);    
    }
});

Flight::route("PUT /myreport/case/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case = new My_report_case();
        $myreport_case->update_case_by_id($id);
    }
});

Flight::route("DELETE /myreport/case/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case = new My_report_case();
        $myreport_case->remove_case($id);
    }
});