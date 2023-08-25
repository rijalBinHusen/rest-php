<?php

Flight::route('GET /', function () {
    include('html/under_contruction/default.html');
});

Flight::route('GET /report/weekly', function () {
    include('html/weekly_report/index.html');
});

Flight::route('GET /myreport_app', function () {
    include('html/myreport_app/index.html');
});

// Flight::route('POST /', function () {
//     $req = Flight::request()->query->name;
//     echo 'I received a POST request ' . $req;
// });

// Flight::route('PUT /', function () {
//     echo 'I received a PUT request.';
// });

// Flight::route('DELETE /', function () {
//     echo 'I received a DELETE request.';
// });
// root route

?>