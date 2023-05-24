<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/base_item_controller.php");

Flight::route('GET /myreport/base_items', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_item = new My_report_base_item();
    $myreport_base_item->get_base_items();
});


Flight::route('POST /myreport/base_item', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }
    $myreport_base_item = new My_report_base_item();
    $myreport_base_item->add_base_item();    
});

Flight::route("GET /myreport/base_item/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_item = new My_report_base_item();
    $myreport_base_item->get_base_item_by_id($id);    
});

Flight::route("PUT /myreport/base_item/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_item = new My_report_base_item();
    $myreport_base_item->update_base_item_by_id($id);
});