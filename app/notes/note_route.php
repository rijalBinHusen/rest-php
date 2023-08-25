<?php
require_once(__DIR__ . "/user_controller.php");
require_once(__DIR__ . "/note_controller.php.php");

Flight::route('POST /note', function () {
    $user = new User_note_app_model();
    $is_token_valid = $user->validate();

    if ($is_token_valid) {

        $myreport_base_file = new note_app();
        $myreport_base_file->add_note();
    } else {

        Flight::json(
            array(
                'success' => false,
                'message' => 'You must be authenticated to access this resource.'
            ),
            401
        );
    }
});

Flight::route('GET /notes', function () {
    $user = new User_note_app_model();
    $is_token_valid = $user->validate();

    if ($is_token_valid) {

        $myreport_base_file = new note_app();
        $myreport_base_file->get_notes();
    } else {

        Flight::json(
            array(
                'success' => false,
                'message' => 'You must be authenticated to access this resource.'
            ),
            401
        );
    }
});


Flight::route("GET /note/@id", function ($id) {
    $user = new User_note_app_model();
    $is_token_valid = $user->validate();

    if ($is_token_valid) {

        $myreport_base_file = new note_app();
        $myreport_base_file->get_note_by_id($id);
    } else {

        Flight::json(
            array(
                'success' => false,
                'message' => 'You must be authenticated to access this resource.'
            ),
            401
        );
    }
});

Flight::route("PUT /note/@id", function ($id) {
    $user = new User_note_app_model();
    $is_token_valid = $user->validate();

    if ($is_token_valid) {

        $myreport_base_file = new note_app();
        $myreport_base_file->update_note_by_id($id);
    } else {

        Flight::json(
            array(
                'success' => false,
                'message' => 'You must be authenticated to access this resource.'
            ),
            401
        );
    }
});

Flight::route("DELETE /note/@id", function ($id) {
    $user = new User_note_app_model();
    $is_token_valid = $user->validate();

    if ($is_token_valid) {

        $myreport_base_file = new note_app();
        $myreport_base_file->remove_note($id);
    } else {

        Flight::json(
            array(
                'success' => false,
                'message' => 'You must be authenticated to access this resource.'
            ),
            401
        );
    }
});
