<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/complain_import_controller.php");

Flight::route('GET /myreport/complains_import', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain_import = new My_report_complain_import();
        $myreport_complain_import->get_complains_import();
    }
});


Flight::route('POST /myreport/complain_import', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $myreport_complain_import = new My_report_complain_import();
        $myreport_complain_import->add_complain_import();    
    }
});

Flight::route("GET /myreport/complain_import/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain_import = new My_report_complain_import();
        $myreport_complain_import->get_complain_import_by_id($id);    
    }
});

Flight::route("PUT /myreport/complain_import/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain_import = new My_report_complain_import();
        $myreport_complain_import->update_complain_import_by_id($id);
    }
});

Flight::route("DELETE /myreport/complain_import/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain_import = new My_report_complain_import();
        $myreport_complain_import->remove_complain_import($id);
    }
});