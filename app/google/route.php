<?php


Flight::route('GET /google/auth_url', function () {
    include('signIn.php');
});
