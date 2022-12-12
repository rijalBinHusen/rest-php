<?php

require_once('vendor/autoload.php');
require_once('controller/My_report_warehouse.php');
// require_once('model/database.php');

// My report rest api
// My report rest api warehouse endpoint
$myreport_warehouse = new My_report_warehouse();
// Flight::route('GET /myreport', array($myreport_warehouse, 'getMyGuests'));
Flight::route('POST /myreport/warehouse', array($myreport_warehouse, 'add_warehouse'));
// Flight::route('DELETE /myreport/warehouse/@id', array($guests, 'deleteGuest'));
Flight::route('GET /myreport/warehouse/@id', array($myreport_warehouse, 'get_warehouse_by_id'));
// Flight::route('PUT /myreport/warehouse/@id', array($guests, 'updateGuestById'));

// My report rest api
// root route
Flight::route('GET /', function () {
    echo 'I received a GET request.';
});

Flight::route('POST /', function () {
    $req = Flight::request()->query->name;
    echo 'I received a POST request ' . $req;
});

Flight::route('PUT /', function () {
    echo 'I received a PUT request.';
});

Flight::route('DELETE /', function () {
    echo 'I received a DELETE request.';
});
// root route

Flight::start();
