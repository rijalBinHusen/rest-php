<?php

Flight::route('GET /myreport/warehouses', function () {
    $user = new User();
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_warehouse = new My_report_warehouse();
    $myreport_warehouse->get_warehouses();
});


Flight::route('POST /myreport/warehouse', function () {
    $user = new User();
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }
    $myreport_warehouse = new My_report_warehouse();
    $myreport_warehouse->add_warehouse();    
});

Flight::route("GET /warehouse/@id", function ($id) {
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_warehouse = new My_report_warehouse();
    $myreport_warehouse->get_warehouse_by_id($id);    
});

Flight::route("GET /warehouse/@id", function ($id) {
    $is_token_valid = $user->check_token();

    if(!$is_token_valid) {
        return;
    }

    $myreport_warehouse = new My_report_warehouse();
    $myreport_warehouse->updadte_warehouse_by_id($id);
});