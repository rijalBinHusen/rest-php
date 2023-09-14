<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/base_file_controller.php");

Flight::route('GET /myreport/base_files', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new My_report_base_file();
        $myreport_base_file->get_base_files();
    }
});


Flight::route('POST /myreport/base_file', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $myreport_base_file = new My_report_base_file();
        $myreport_base_file->add_base_file();    
    }
});

Flight::route("GET /myreport/base_file/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new My_report_base_file();
        $myreport_base_file->get_base_file_by_id($id);    
    }
});

Flight::route("PUT /myreport/base_file/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new My_report_base_file();
        $myreport_base_file->update_base_file_by_id($id);
    }
});

Flight::route("DELETE /myreport/base_file/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new My_report_base_file();
        $myreport_base_file->remove_base_file($id);
    }
});