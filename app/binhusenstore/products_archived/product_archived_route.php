<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/product_archived_controller.php");

Flight::route('GET /binhusenstore/products_archived', function () {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product_archived();
        $binhusenstore_product->get_products();
    }
});

Flight::route("GET /binhusenstore/product_archived/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product_archived();
        $binhusenstore_product->get_product_by_id($id);
    }
});

Flight::route("DELETE /binhusenstore/product_archived/@id", function ($id) {
    $user = new User("binhusenstore_users");
    $is_admin = $user->is_admin(1);

    if ($is_admin) {

        $binhusenstore_product = new Binhusenstore_product_archived();
        $binhusenstore_product->remove_product($id);
    }
});
