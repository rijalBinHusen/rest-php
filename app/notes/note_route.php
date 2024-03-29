<?php
require_once(__DIR__ . "/note_controller.php");
require_once(__DIR__ ."/../Users/user_controller.php");

Flight::route('POST /note', function () {
    $user = new User("note_app_users");
    $user_info = $user->get_user_info();

    if ($user_info) {
        $user_id = $user_info->data->id;

        $myreport_base_file = new note_app();
        $myreport_base_file->add_note($user_id);
    }
});

Flight::route('GET /notes', function () {
    $user = new User("note_app_users");
    $user_info = $user->get_user_info();

    if ($user_info) {

        $myreport_base_file = new note_app();
        $myreport_base_file->get_notes();
    }
});


Flight::route("GET /note/@id", function ($id) {

    $myreport_base_file = new note_app();
    $myreport_base_file->get_note_by_id($id);
});

Flight::route("PUT /note/@id", function ($id) {
    $user = new User("note_app_users");
    $user_info = $user->get_user_info();

    if ($user_info) {

        $myreport_base_file = new note_app();
        $myreport_base_file->update_note_by_id($id, $user_info->data->id);
    }
});

Flight::route("DELETE /note/@id", function ($id) {
    $user = new User("note_app_users");
    $user_info = $user->get_user_info();

    if ($user_info) {

        $myreport_base_file = new note_app();
        $myreport_base_file->remove_note($id);
    }
});
