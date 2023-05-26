<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/field_problem_controller.php");

Flight::route('GET /myreport/field_problems', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_field_problem = new My_report_field_problem();
    $myreport_field_problem->get_field_problems();
});


Flight::route('POST /myreport/field_problem', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }
    $myreport_field_problem = new My_report_field_problem();
    $myreport_field_problem->add_field_problem();    
});

Flight::route("GET /myreport/field_problem/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_field_problem = new My_report_field_problem();
    $myreport_field_problem->get_field_problem_by_id($id);    
});

Flight::route("PUT /myreport/field_problem/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_field_problem = new My_report_field_problem();
    $myreport_field_problem->update_field_problem_by_id($id);
});

Flight::route("DELETE /myreport/field_problem/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_field_problem = new My_report_field_problem();
    $myreport_field_problem->remove_field_problem($id);
});