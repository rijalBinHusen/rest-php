<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/admin_charge_controller.php");

Flight::route('POST /binhusenstore/admin_charge', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_admin(1);

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_admin_charge();
        $myreport_base_file->add_admin_charge();    
    }
});

Flight::route("GET /binhusenstore/admin_charge", function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new Binhusenstore_admin_charge();
        $myreport_base_file->get_admin_charge();    
    }

});

Flight::route("PUT /binhusenstore/admin_charge/", function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_admin(1)();

    if($is_token_valid) {
        
        $myreport_base_file = new Binhusenstore_admin_charge();
        $myreport_base_file->update_admin_charge_by_id($id);
    }

});