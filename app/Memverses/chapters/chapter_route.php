<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/chapter_controller.php");


Flight::route('POST /memverses/chapter', function () {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->add_chapter($user_info->data->id, $user_info->data->device_id);
    }
});

Flight::route('POST /memverses/chapter_and_verses', function () {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->add_chapter_and_verses($user_info->data->id, $user_info->data->device_id);
    }
});

Flight::route('GET /memverses/chapters/@id_folder', function ($id_folder) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->get_chapters($user_info->data->id, $id_folder, $user_info->data->device_id);
    }
});

Flight::route("GET /memverses/chapter/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->get_chapter_by_id($user_info->data->id, $id);
    }
});

Flight::route("PUT /memverses/read/chapter/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->update_readed($id, $user_info->data->id, $user_info->data->device_id);
    }
});

Flight::route("PUT /memverses/move_to_folder/chapter/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->update_folder($id, $user_info->data->id, $user_info->data->device_id);
    }
});

Flight::route("PUT /memverses/reset_readed_times/folder/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->reset_readed($id, $user_info->data->id, $user_info->data->device_id);
    }
});

// Flight::route('GET /memverses/unread_verses/@id', function ($id_folder) {
//     $user = new User("memverses_users");
//     $user_info = $user->get_user_info(true);

//     if ($user_info && $user_info->data->id) {

//         $memverses_chapter = new Memverses_chapter();
//         $memverses_chapter->get_unreaded_verses($user_info->data->id, $id_folder, $user_info->data->device_id);
//     }
// });

// Flight::route("DELETE /memverses/chapter/@id", function ($id) {
//     $user = new User("memverses_users");
//     $user_info = $user->get_user_info();

//     if ($user_info && $user_info->data->id) {

//         $memverses_chapter = new My_report_chapter();
//         $memverses_chapter->remove_chapter($id);
//     }
// });
