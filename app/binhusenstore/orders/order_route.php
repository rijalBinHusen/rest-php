<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/order_controller.php.php");

Flight::route('POST /binhusenstore/order', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->add_order();    
    }
});

Flight::route('GET /binhusenstore/orders', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->get_orders();
    }

});


Flight::route("GET /binhusenstore/order/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->get_order_by_id($id);    
    }

});

Flight::route("PUT /binhusenstore/order/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->update_order_by_id($id);
    }

});

Flight::route("DELETE /binhusenstore/order/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->remove_order($id);
    }

});