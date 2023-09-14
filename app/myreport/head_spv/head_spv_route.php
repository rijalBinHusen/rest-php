<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/head_spv_controller.php");

Flight::route('GET /myreport/heads_spv', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_head_spv = new My_report_head_spv();
        $myreport_head_spv->get_heads_spv();
    }
});


Flight::route('POST /myreport/head_spv', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        $myreport_head_spv = new My_report_head_spv();
        $myreport_head_spv->add_head_spv();    
    }
});

Flight::route("GET /myreport/head_spv/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_head_spv = new My_report_head_spv();
        $myreport_head_spv->get_head_spv_by_id($id);    
    }
});

Flight::route("PUT /myreport/head_spv/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_head_spv = new My_report_head_spv();
        $myreport_head_spv->update_head_spv_by_id($id);
    }
});