<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/case_import_controller.php");

Flight::route('GET /myreport/cases_import', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case_import = new My_report_case_import();
        $myreport_case_import->get_cases_import();
    }
});


Flight::route('POST /myreport/case_import', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $myreport_case_import = new My_report_case_import();
        $myreport_case_import->add_case_import();    
    }
});

Flight::route("GET /myreport/case_import/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case_import = new My_report_case_import();
        $myreport_case_import->get_case_import_by_id($id);    
    }
});

Flight::route("PUT /myreport/case_import/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case_import = new My_report_case_import();
        $myreport_case_import->update_case_import_by_id($id);
    }
});

Flight::route("DELETE /myreport/case_import/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_case_import = new My_report_case_import();
        $myreport_case_import->remove_case_import($id);
    }
});