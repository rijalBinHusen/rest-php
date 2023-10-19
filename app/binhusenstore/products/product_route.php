<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/product_controller.php");

Flight::route('POST /binhusenstore/product', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->add_product();    
    }
});

Flight::route('GET /binhusenstore/products', function () {
    // $user = new User("binhusenstore_users");
    // $is_token_valid = $user->is_valid_token();

    // if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->get_products();
    // }

});


Flight::route("GET /binhusenstore/product/@id", function ($id) {
    // $user = new User("binhusenstore_users");
    // $is_token_valid = $user->is_valid_token();

    // if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->get_product_by_id($id);    
    // }

});

Flight::route("PUT /binhusenstore/product/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->update_product_by_id($id);
    }
});

Flight::route("DELETE /binhusenstore/product/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
            
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->remove_product($id);
    }
});

Flight::route("GET /binhusenstore/products/landing_page", function () {
    // $user = new User("binhusenstore_users");
    // $is_token_valid = $user->is_valid_token();

    // if($is_token_valid) {
        
    $myreport_base_file = new Binhusenstore_product();
    $myreport_base_file->get_products_for_landing_page();
    // }
});

Flight::route('GET /binhusenstore/products/count', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->get_count_products();
    }
});

Flight::route('POST /binhusenstore/product/move_to_archive', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_product();
        $myreport_base_file->move_product_to_archive();
    }
});