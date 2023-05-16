<?php

// register
Flight::route('POST /user/register', function () {
    $user = new User();
    $user->register();
});

// login
Flight::route('POST /user/login', function () {
    $user = new User();
    $user->login();
});

// validate
Flight::route('POST /user/validate', function () {
    $user = new User();
    $user->check_token();
});