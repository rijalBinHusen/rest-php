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