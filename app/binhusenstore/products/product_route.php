<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/product_controller.php");
require_once(__DIR__ . "/../../AccessCode/access_code_controller.php");

Flight::route('POST /binhusenstore/product', function () {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->add_product();
    }
});

Flight::route('GET /binhusenstore/products', function () {
    $access_code = new Access_code();
    $is_valid_code = $access_code->validate_code_on_header("binhusenstore");

    if ($is_valid_code) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->get_products();
    }
});

Flight::route("GET /binhusenstore/product/@id", function ($id) {
    $access_code = new Access_code();
    $is_valid_code = $access_code->validate_code_on_header("binhusenstore");

    if ($is_valid_code) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->get_product_by_id($id);
    }
});

Flight::route("PUT /binhusenstore/product/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->update_product_by_id($id);
    }
});

Flight::route("DELETE /binhusenstore/product/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->remove_product($id);
    }
});

Flight::route("GET /binhusenstore/products/landing_page", function () {
    $access_code = new Access_code();
    $is_valid_code = $access_code->validate_code_on_header("binhusenstore");

    if ($is_valid_code) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->get_products_for_landing_page();
    }
});

Flight::route('GET /binhusenstore/products/count', function () {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->get_count_products();
    }
});

Flight::route('POST /binhusenstore/product/move_to_archive', function () {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->move_product_to_archive();
    }
});

Flight::route('GET /binhusenstore/products_and_details', function () {
    
    $access_code = new Access_code();
    $is_valid_code = $access_code->validate_code_on_header("binhusenstore");

    if ($is_valid_code) {

        $binhusenstore_product = new Binhusenstore_product();
        $binhusenstore_product->get_products_detail();
    }
});