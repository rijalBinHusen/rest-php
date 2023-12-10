<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/date_controller.php");

Flight::route('POST /binhusenstore/date', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_admin(1);

    if($is_token_valid) {
        
        $date = new Binhusenstore_date();
        $date->add_date();    
    }
});

Flight::route('GET /binhusenstore/dates', function () {
    $access_code = new Access_code();
    $is_valid_code = $access_code->validate_code_on_header("binhusenstore");

    if($is_valid_code) {
        
        $date = new Binhusenstore_date();
        $date->get_dates();
    }

});

Flight::route("PUT /binhusenstore/date/@year", function ($year) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_admin(1);

    if($is_token_valid) {
        
        $date = new Binhusenstore_date();
        $date->update_date($year);
    }

});

Flight::route("DELETE /binhusenstore/date/@year", function ($year) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $date = new Binhusenstore_date();
        $date->remove_date($year);
    }

});