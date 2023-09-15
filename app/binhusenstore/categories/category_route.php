<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/category_controller.php");

Flight::route('POST /binhusenstore/category', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_category();
        $myreport_base_file->add_category();    
    }
});

Flight::route('GET /binhusenstore/categories', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_category();
        $myreport_base_file->get_categories();
    }

});


Flight::route("GET /binhusenstore/category/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_category();
        $myreport_base_file->get_category_by_id($id);    
    }

});

Flight::route("PUT /binhusenstore/category/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_category();
        $myreport_base_file->update_category_by_id($id);
    }

});

Flight::route("DELETE /binhusenstore/category/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_category();
        $myreport_base_file->remove_category($id);
    }

});