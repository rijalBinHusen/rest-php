<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/image_controller.php");

Flight::route('POST /binhusenstore/image', function () {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $binhusenstore_image = new Binhusenstore_image();
        $binhusenstore_image->upload_image();    
    }
});

Flight::route("DELETE /binhusenstore/category/@filename", function ($filename) {
    $user = new User("binhusenstore_users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $binhusenstore_image = new Binhusenstore_image();
        $binhusenstore_image->remove_image($filename);
    }

});