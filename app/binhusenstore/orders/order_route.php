<?php
require_once(__DIR__ . "/order_controller.php");

Flight::route('POST /binhusenstore/order', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->add_order();    
    }
});

Flight::route('GET /binhusenstore/orders', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->get_orders();
    }

});


Flight::route("GET /binhusenstore/order/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->get_order_by_id($id);    
    }

});

Flight::route("GET /binhusenstore/orders/count", function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->get_count_orders();    
    }

});

Flight::route("PUT /binhusenstore/order/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->update_order_by_id($id);
    }

});

Flight::route("DELETE /binhusenstore/order/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_order();
        $myreport_base_file->remove_order($id);
    }

});