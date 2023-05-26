<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/base_stock_controller.php");

Flight::route('POST /myreport/base_stock', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }
    $myreport_base_stock = new My_report_base_stock();
    $myreport_base_stock->add_base_stock();    
});

Flight::route('GET /myreport/base_stocks', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_stock = new My_report_base_stock();
    $myreport_base_stock->get_base_stock_by_parent();
});

Flight::route('DELETE /myreport/base_stocks', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_stock = new My_report_base_stock();
    $myreport_base_stock->get_base_stock_by_parent();
});

Flight::route("GET /myreport/base_stock/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_stock = new My_report_base_stock();
    $myreport_base_stock->get_base_stock_by_id($id);    
});

Flight::route("PUT /myreport/base_stock/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_stock = new My_report_base_stock();
    $myreport_base_stock->update_base_stock_by_id($id);
});

Flight::route("DELETE /myreport/base_stock/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_base_stock = new My_report_base_stock();
    $myreport_base_stock->remove_base_stock($id);
});