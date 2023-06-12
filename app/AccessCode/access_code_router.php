<?php
require_once(__DIR__ . '/access_code_controller.php');

Flight::route('POST /access_code/create', function () {
    $access_code = new Access_code();
    $access_code->create_access_code();
});

Flight::route('POST /access_code/validate', function () {
    $access_code = new Access_code();
    $access_code->validate_code();
});