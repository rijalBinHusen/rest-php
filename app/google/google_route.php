<?php
require_once(__DIR__ . "/google_sign_controller.php");

Flight::route('GET /google/auth_url', function () {
    $google_sign_in = new Google_sign_controller();
    $google_sign_in->generate_auth_url();
});

Flight::route('GET /google/redirect_to_application', function () {
    $google_sign_in = new Google_sign_controller();
    $google_sign_in->redirect_to_origin_url();
});

Flight::route('GET /google/get_access_token', function () {
    $google_sign_in = new Google_sign_controller();
    $google_sign_in->getAccessToken();
});

Flight::route('GET /google/get_user_info', function () {
    $google_sign_in = new Google_sign_controller();
    $google_sign_in->getUserInfo();
});

Flight::route('GET /google/sign_out', function () {
    $google_sign_in = new Google_sign_controller();
    $google_sign_in->sign_out();
});
