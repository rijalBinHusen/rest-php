<?php

require_once('vendor/autoload.php');
require_once('controller/My_report_warehouse.php');
require_once('controller/My_report_supervisor.php');
// require_once('model/database.php');

// My report rest api
// My report rest api warehouse endpoint
$myreport_warehouse = new My_report_warehouse();
Flight::route('GET /myreport/warehouses', array($myreport_warehouse, 'get_warehouses'));
Flight::route('POST /myreport/warehouse', array($myreport_warehouse, 'add_warehouse'));
Flight::route('GET /myreport/warehouse/@id', array($myreport_warehouse, 'get_warehouse_by_id'));
Flight::route('PUT /myreport/warehouse/@id', array($myreport_warehouse, 'update_warehouse_by_id'));
// My report rest api warehouse endpoint

// my report supervisor
$myreport_supervisor = new My_report_supervisor();
Flight::route('GET /myreport/supervisors', array($myreport_supervisor, 'get_supervisors'));
Flight::route('POST /myreport/supervisor', array($myreport_supervisor, 'add_supervisor'));
Flight::route('GET /myreport/supervisor/@id', array($myreport_supervisor, 'get_supervisor_by_id'));
Flight::route('PUT /myreport/supervisor/@id', array($myreport_supervisor, 'update_supervisor_by_id'));
// my report supervisor

// my report base item
// my report base item

// my report head supervisor
// my report head supervisor

// my report problem
// my report problem

// my report base file
// my report base file

// my report field problem
// my report field problem

// my report document
// my report document

// my report complain
// my report complain

// my report complain import
// my report complain import

// my report case
// my report case

// my report case import
// my report case import

// my report base stock
// my report base stock

// my report base clock
// my report base clock

// My report rest api

// root route for testing
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
