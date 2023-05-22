<?php

require_once('vendor/autoload.php');
require_once(__DIR__ . '/app/tests_route.php');
// require_once(__DIR__ . '/app/myreport/warehouse/warehouse_route.php');
require_once(__DIR__ . '/app/Users/user_route.php');
// require_once('controller/My_report_supervisor.php');
// require_once('controller/My_report_base_item.php');
// require_once('controller/My_report_head_spv.php');
// require_once('controller/My_report_problem.php');
// require_once('controller/My_report_base_file.php');
// require_once('controller/My_report_field_problem.php');
// require_once('controller/User.php');

// Flight::before('/*', function() {
//     // Get the token from the request header
//     $jwt_token = Flight::request()->headers['Authorization'];

//     // Set the token in the request header
//     if (!empty($jwt_token)) {
//         Flight::response()->header('Authorization', $jwt_token);
//     }
// });

Flight::route('/blank(/@endpoint)', function ($endpoint) {
    $db = new Query_builder();
    $stmt = $db->select_from('users')->fetchAll(PDO::FETCH_ASSOC);
    Flight::json($stmt);
});

Flight::route('/test(/@endpoint)', function ($endpoint) {
    $request = Flight::request();
    // $jwt_token = $request->;

    Flight::json([
        'url' => $request->url,
        'base' => $request->base,
        'method' => $request->method,
        'referrer' => $request->referrer,
        'ip' => $request->ip,
        'ajax' => $request->ajax,
        'scheme' => $request->scheme,
        'user_agent' => $request->user_agent,
        'type' => $request->type,
        'length' => $request->length,
        'query' => $request->query,
        'data' => $request->data,
        'cookies' => $request->cookies,
        'files' => $request->files,
        'secure' => $request->secure,
        'accept' => $request->accept,
        'proxy_ip' => $request->proxy_ip,
        'end_point' => $endpoint,
        'name' => $request->data->name
    ]);

});

// Flight::route('/myreport(/@endpoint)', function ($endpoint) {
//     $user = new User();
//     $is_token_valid = $user->check_token();

//     if(!$is_token_valid) {
//         return;
//     }

//     $request = Flight::request();
//     $is_endpoint_warehouse = $endpoint === 'warehouses' || $endpoint === 'warehouse';
    
//     if($is_endpoint_warehouse) {
//         $is_get_warehouses = $endpoint === 'warehouses' && $request->method === 'GET';
//         $is_post_warehouse = $endpoint === 'warehouse' && $request->method === 'POST';

//         $myreport_warehouse = new My_report_warehouse();
//         if($is_get_warehouses) {
//             $myreport_warehouse->get_warehouses();
//         } 
//         else if ($is_post_warehouse) {
//             $myreport_warehouse->add_warehouse();
//         }
//     }
// });

// $myreport_warehouse = new My_report_warehouse();
// Flight::route('GET /myreport/warehouses', array($myreport_warehouse, 'get_warehouses'));
// Flight::route('POST /myreport/warehouse', array($myreport_warehouse, 'add_warehouse'));
// Flight::route('GET /myreport/warehouse/@id', array($myreport_warehouse, 'get_warehouse_by_id'));
// Flight::route('PUT /myreport/warehouse/@id', array($myreport_warehouse, 'update_warehouse_by_id'));
// My report rest api warehouse endpoint

// my report supervisor
// $myreport_supervisor = new My_report_supervisor();
// Flight::route('GET /myreport/supervisors', array($myreport_supervisor, 'get_supervisors'));
// Flight::route('POST /myreport/supervisor', array($myreport_supervisor, 'add_supervisor'));
// Flight::route('GET /myreport/supervisor/@id', array($myreport_supervisor, 'get_supervisor_by_id'));
// Flight::route('PUT /myreport/supervisor/@id', array($myreport_supervisor, 'update_supervisor_by_id'));
// // my report supervisor

// // my report base item
// $myreport_base_item = new My_report_base_item();
// Flight::route('GET /myreport/base_items', array($myreport_base_item, 'get_items'));
// Flight::route('POST /myreport/base_item', array($myreport_base_item, 'add_item'));
// Flight::route('GET /myreport/base_item/@id', array($myreport_base_item, 'get_item_by_id'));
// Flight::route('PUT /myreport/base_item/@id', array($myreport_base_item, 'update_item_by_id'));
// Flight::route('DELETE /myreport/base_item/@id', array($myreport_base_item, 'delete_item'));
// // my report base item

// // my report head supervisor
// $myreport_head_spv = new My_report_head_spv();
// Flight::route('GET /myreport/heads_spv', array($myreport_head_spv, 'get_heads_spv'));
// Flight::route('POST /myreport/head_spv', array($myreport_head_spv, 'add_head_spv'));
// Flight::route('GET /myreport/head_spv/@id', array($myreport_head_spv, 'get_head_spv_by_id'));
// Flight::route('PUT /myreport/head_spv/@id', array($myreport_head_spv, 'update_head_spv_by_id'));
// // my report head supervisor

// // my report problem
// $myreport_problem = new My_report_problem();
// Flight::route('GET /myreport/problems', array($myreport_problem, 'get_problems'));
// Flight::route('POST /myreport/problem', array($myreport_problem, 'add_problem'));
// Flight::route('GET /myreport/problem/@id', array($myreport_problem, 'get_problem_by_id'));
// Flight::route('PUT /myreport/problem/@id', array($myreport_problem, 'update_problem_by_id'));
// // my report problem

// // my report base file
// $myreport_base_file = new My_report_base_file();
// // get base files between two periode
// Flight::route('GET /myreport/base_files', array($myreport_base_file, 'get_base_files'));
// Flight::route('POST /myreport/base_file', array($myreport_base_file, 'add_base_file'));
// Flight::route('GET /myreport/base_file/@id', array($myreport_base_file, 'get_base_file_by_id'));
// Flight::route('PUT /myreport/base_file/@id', array($myreport_base_file, 'update_base_file_by_id'));
// Flight::route('DELETE /myreport/base_file/@id', array($myreport_base_file, 'delete_base_file'));
// // my report base file

// // my report field problem
// $myreport_problem = new My_report_field_problem();
// Flight::route('GET /myreport/problems', array($myreport_problem, 'get_field_problem'));
// Flight::route('POST /myreport/problem', array($myreport_problem, 'add_field_problem'));
// Flight::route('GET /myreport/problem/@id', array($myreport_problem, 'get_field_problem_by_id'));
// Flight::route('PUT /myreport/problem/@id', array($myreport_problem, 'update_field_problem_by_id'));
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

Flight::map('notFound', function(){
    // Handle 404 errors
    Flight::json([
        "success" => false,
        "message" => "Page not found" 
    ], 404);
});


Flight::start();
