<?php
require_once(__DIR__ . "/cart_controller.php");

Flight::route('POST /binhusenstore/cart', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_cart();
        $myreport_base_file->add_cart();    
    }
});

Flight::route('GET /binhusenstore/carts', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_cart();
        $myreport_base_file->get_carts();
    }

});


Flight::route("GET /binhusenstore/cart/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_cart();
        $myreport_base_file->get_cart_by_id($id);    
    }

});

Flight::route("PUT /binhusenstore/cart/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_cart();
        $myreport_base_file->update_cart_by_id($id);
    }

});

Flight::route("DELETE /binhusenstore/cart/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        $myreport_base_file = new Binhusenstore_cart();
        $myreport_base_file->remove_cart($id);
    }

});