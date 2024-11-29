<?php

// contant name variable
require_once(__DIR__ . '/utils/constant_named.php');

require_once('vendor/autoload.php');
require_once(__DIR__ . '/app/index_route.php');
require_once(__DIR__ . '/utils/cors.php');
require_once(__DIR__ . '/app/Users/user_route.php');
require_once(__DIR__ . '/app/AccessCode/access_code_router.php');

// myreport app
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
require_once(__DIR__ . '/app/myreport/base_stock/base_stock_route.php');
require_once(__DIR__ . '/app/myreport/base_clock/base_clock_route.php');
require_once(__DIR__ . '/app/myreport/problem/problem_route.php');
require_once(__DIR__ . '/app/myreport/document/document_route.php');
require_once(__DIR__ . '/app/myreport/users/user_route.php');
require_once(__DIR__ . '/app/myreport/reports/report_route.php');

// note app
require_once(__DIR__ . '/app/notes/user_route.php');
require_once(__DIR__ . '/app/notes/note_route.php');

// binhusenstore
require_once(__DIR__ . '/app/binhusenstore/access_code_router.php');
require_once(__DIR__ . '/app/binhusenstore/user_route.php');
require_once(__DIR__ . '/app/binhusenstore/categories/category_route.php');
require_once(__DIR__ . '/app/binhusenstore/carts/cart_route.php');
require_once(__DIR__ . '/app/binhusenstore/orders/order_route.php');
require_once(__DIR__ . '/app/binhusenstore/payments/payments_route.php');
require_once(__DIR__ . '/app/binhusenstore/products/product_route.php');
require_once(__DIR__ . '/app/binhusenstore/testimonies/testimony_route.php');
require_once(__DIR__ . '/app/binhusenstore/images/image_route.php');
require_once(__DIR__ . '/app/binhusenstore/date_end/date_route.php');
require_once(__DIR__ . '/app/binhusenstore/admin_charge/admin_charge_route.php');
require_once(__DIR__ . '/app/binhusenstore/products_archived/product_archived_route.php');

// memverses
require_once(__DIR__ . '/app/Memverses/user_route.php');
require_once(__DIR__ . '/app/Memverses/chapters/chapter_route.php');
require_once(__DIR__ . '/app/Memverses/folder/folder_route.php');

// google
require_once(__DIR__ . '/app/google/route.php');

require_once(__DIR__ . '/app/toy/queue_route.php');

// Flight::route('/test(/@endpoint)', function ($endpoint) {
//     $request = Flight::request();
//     // $jwt_token = $request->;

//     Flight::json([
//         'url' => $request->url,
//         'base' => $request->base,
//         'method' => $request->method,
//         'referrer' => $request->referrer,
//         'ip' => $request->ip,
//         'ajax' => $request->ajax,
//         'scheme' => $request->scheme,
//         'user_agent' => $request->user_agent,
//         'type' => $request->type,
//         'length' => $request->length,
//         'query' => $request->query,
//         'data' => $request->data,
//         'cookies' => $request->cookies,
//         'files' => $request->files,
//         'secure' => $request->secure,
//         'accept' => $request->accept,
//         'proxy_ip' => $request->proxy_ip,
//         'referrer' => $request->referrer,
//         'end_point' => $endpoint,
//         'name' => $request->data->name,
//     ]);
// });

Flight::map('notFound', function () {
    // Handle 404 errors
    Flight::json([
        "success" => false,
        "message" => "Page not found"
    ], 404);
});


Flight::start();
