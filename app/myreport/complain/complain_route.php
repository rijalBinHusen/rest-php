<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/complain_controller.php");

Flight::route('GET /myreport/complains', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain = new My_report_complain();
        $myreport_complain->get_complains();
    }
});


Flight::route('POST /myreport/complain', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $myreport_complain = new My_report_complain();
        $myreport_complain->add_complain();    
    }
});

Flight::route("GET /myreport/complain/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain = new My_report_complain();
        $myreport_complain->get_complain_by_id($id);    
    }
});

Flight::route("PUT /myreport/complain/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain = new My_report_complain();
        $myreport_complain->update_complain_by_id($id);
    }
});

Flight::route("DELETE /myreport/complain/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_complain = new My_report_complain();
        $myreport_complain->remove_complain($id);
    }
});