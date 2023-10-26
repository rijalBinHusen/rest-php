<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/testimony_controller.php");

Flight::route('POST /binhusenstore/testimony', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_testimony();
        $myreport_base_file->add_testimony();    
    }
});

Flight::route('GET /binhusenstore/testimonies', function () {
    // $user = new User("binhusenstore_users");
    // $is_token_valid = $user->is_valid_token();

    // if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_testimony();
        $myreport_base_file->get_testimonies();
    // }

});

Flight::route("GET /binhusenstore/testimony/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_testimony();
        $myreport_base_file->get_testimony_by_id($id);    
    }

});

Flight::route("GET /binhusenstore/testimonies/landing_page", function () {
    // $user = new User("binhusenstore_users");
    // $is_token_valid = $user->is_valid_token();

    // if($is_token_valid) {
       
    $myreport_base_file = new Binhusenstore_testimony();
    $myreport_base_file->get_testimony_for_landing_page();    
    // }

});

Flight::route("PUT /binhusenstore/testimony/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_testimony();
        $myreport_base_file->update_testimony_by_id($id);
    }

});

Flight::route("DELETE /binhusenstore/testimony/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_testimony();
        $myreport_base_file->remove_testimony($id);
    }

});