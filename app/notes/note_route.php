<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/note_controller.php.php");

Flight::route('POST /note', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new note_app();
        $myreport_base_file->add_note();    
    }
});

Flight::route('GET /notes', function () {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new note_app();
        $myreport_base_file->get_notes();
    }

});


Flight::route("GET /note/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
       
        $myreport_base_file = new note_app();
        $myreport_base_file->get_note_by_id($id);    
    }

});

Flight::route("PUT /note/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new note_app();
        $myreport_base_file->update_note_by_id($id);
    }

});

Flight::route("DELETE /note/@id", function ($id) {
    $user = new User();
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_base_file = new note_app();
        $myreport_base_file->remove_note($id);
    }

});