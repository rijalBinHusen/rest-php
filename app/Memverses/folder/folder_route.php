<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/folder_controller.php");


Flight::route('POST /memverses/folder', function () {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info();

    if ($user_info && $user_info->data->id) {

        $memverses_folder = new Memverses_folder();
        $memverses_folder->add_folder($user_info->data->id);
    }
});

Flight::route('GET /memverses/folders', function () {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info();

    if ($user_info && $user_info->data->id) {

        $memverses_folder = new Memverses_folder();
        $memverses_folder->get_folders($user_info->data->id);
    }
});

Flight::route("GET /memverses/folder/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info();

    if ($user_info && $user_info->data->id) {

        $memverses_folder = new Memverses_folder();
        $memverses_folder->get_folder_by_id($user_info->data->id, $id);
    }
});

Flight::route("PUT /memverses/folder/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info();

    if ($user_info && $user_info->data->id) {

        $memverses_folder = new Memverses_folder();
        $memverses_folder->update_folder_by_id($id, $user_info->data->id);
    }
});

// Flight::route("DELETE /memverses/folder/@id", function ($id) {
//     $user = new User("memverses_users");
//     $user_info = $user->get_user_info();

//     if ($user_info && $user_info->data->id) {

//         $memverses_folder = new My_report_folder();
//         $memverses_folder->remove_folder($id);
//     }
// });
