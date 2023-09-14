<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/problem_controller.php");

Flight::route('POST /myreport/problem', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->add_problem();    
    }
});

Flight::route('GET /myreport/problems/byperiode', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->get_problem_by_periode();
    }
});

Flight::route('GET /myreport/problems/bystatus', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->get_problem_by_status();
        
    }
});

Flight::route('GET /myreport/problems/bysupervisor', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {    
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->get_problem_by_supervisor();
    }
});

Flight::route('GET /myreport/problems/bywarehouseanditem', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->get_problem_by_warehouse_and_item();
    }
});

Flight::route("GET /myreport/problem/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->get_problem_by_id($id);    
    }
});

Flight::route("PUT /myreport/problem/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_problem = new My_report_problem();
        $myreport_problem->update_problem_by_id($id);
    }
});