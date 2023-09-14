<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/warehouse_controller.php");

Flight::route('GET /myreport/warehouses', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
    
        $myreport_warehouse = new My_report_warehouse();
        $myreport_warehouse->get_warehouses();
    }
});

Flight::route('POST /myreport/warehouse', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_warehouse = new My_report_warehouse();
        $myreport_warehouse->add_warehouse();    
    }
});

Flight::route("GET /myreport/warehouse/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_warehouse = new My_report_warehouse();
        $myreport_warehouse->get_warehouse_by_id($id);    
    }

});

Flight::route("PUT /myreport/warehouse/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
    
        $myreport_warehouse = new My_report_warehouse();
        $myreport_warehouse->update_warehouse_by_id($id);
    }
});

Flight::route('GET /myreport/warehouse_/last_id', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_warehouse = new My_report_warehouse();
        $myreport_warehouse->get_last_id();    
    }
});