<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/chapter_controller.php");


Flight::route('POST /memverses/chapter', function () {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->add_chapter($user_info->data->id);
    }
});

Flight::route('GET /memverses/chapters/@id_folder', function ($id_folder) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->get_chapters($user_info->data->id, $id_folder);
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

Flight::route("PUT /memverses/chapter/@id", function ($id) {
    $user = new User("memverses_users");
    $user_info = $user->get_user_info(true);

    if ($user_info && $user_info->data->id) {

        $memverses_chapter = new Memverses_chapter();
        $memverses_chapter->update_chapter_by_id($id, $user_info->data->id);
    }
});

// Flight::route("DELETE /memverses/chapter/@id", function ($id) {
//     $user = new User("memverses_users");
//     $user_info = $user->get_user_info();

//     if ($user_info && $user_info->data->id) {

//         $memverses_chapter = new My_report_chapter();
//         $memverses_chapter->remove_chapter($id);
//     }
// });
