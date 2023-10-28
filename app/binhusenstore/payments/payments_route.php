<?php
require_once(__DIR__ . "/payments_controller.php");

Flight::route('POST /binhusenstore/payment', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->add_payment();    
    }
});

Flight::route('GET /binhusenstore/payments', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->get_payments();
    }

});


Flight::route("GET /binhusenstore/payment/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->get_payment_by_id($id);    
    }

});

Flight::route("PUT /binhusenstore/payment/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->update_payment_by_id($id);
    }

});

Flight::route("DELETE /binhusenstore/payment/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->remove_payment($id);
    }

});

Flight::route("PUT /binhusenstore/payment_mark_as_paid", function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->mark_payment_as_paid();
    }

});

Flight::route("GET /binhusenstore/payments/sum_balance", function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_payment();
        $myreport_base_file->get_sum_balance_payments();    
    }

});