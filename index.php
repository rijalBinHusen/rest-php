<?php

require_once('vendor/autoload.php');
require_once(__DIR__ . '/app/tests_route.php');
require_once(__DIR__ . '/app/Users/user_route.php');
require_once(__DIR__ . '/app/myreport/warehouse/warehouse_route.php');
require_once(__DIR__ . '/app/myreport/supervisor/supervisor_route.php');
require_once(__DIR__ . '/app/myreport/head_spv/head_spv_route.php');
require_once(__DIR__ . '/app/myreport/base_item/base_item_route.php');
require_once(__DIR__ . '/app/myreport/complain/complain_route.php');
require_once(__DIR__ . '/app/myreport/complain_import/complain_import_route.php');
require_once(__DIR__ . '/app/myreport/case/case_route.php');
require_once(__DIR__ . '/app/myreport/case_import/case_import_route.php');
require_once(__DIR__ . '/app/myreport/field_problem/field_problem_route.php');
require_once(__DIR__ . '/app/myreport/base_file/base_file_route.php');

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

Flight::map('notFound', function(){
    // Handle 404 errors
    Flight::json([
        "success" => false,
        "message" => "Page not found" 
    ], 404);
});


Flight::start();
