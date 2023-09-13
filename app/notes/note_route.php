<?php
require_once(__DIR__ . "/note_users/user_controller.php");
require_once(__DIR__ . "/note_controller.php");

Flight::route('POST /note', function () {
    $user = new Note_app_user_controller();
    $user_info = $user->get_user_info();

    if ($user_info) {
        $user_id = $user_info['id'];

        $myreport_base_file = new note_app();
        $myreport_base_file->add_note($user_id);
    }
});

Flight::route('GET /notes', function () {
    $user = new Note_app_user_controller();
    $user_info = $user->get_user_info();

    if ($user_info) {

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

    $myreport_base_file = new note_app();
    $myreport_base_file->get_note_by_id($id);
});

Flight::route("PUT /note/@id", function ($id) {
    $user = new Note_app_user_controller();
    $user_info = $user->get_user_info();

    if ($user_info) {

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
    $user = new Note_app_user_controller();
    $user_info = $user->get_user_info();

    if ($user_info) {

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
