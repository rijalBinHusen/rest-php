<?php
require_once(__DIR__ . '/product_model.php');

class Binhusenstore_product
{
    protected $Binhusenstore_product;
    function __construct()
    {
        $this->Binhusenstore_product = new Binhusenstore_product_model();
    }

    public function add_product()
    {
        // request
        $req = Flight::request();
        $name = $req->data->name;
        $categories = $req->data->categories;
        $price = $req->data->price;
        $weight = $req->data->weight;
        $images = $req->data->images;
        $description = $req->data->description;
        $default_total_week = $req->data->default_total_week;
        $is_available = $req->data->is_available;
        $is_admin_charge = $req->data->is_admin_charge;
        $links = $req->data->links;

        $result = null;

        $is_request_body_not_oke = is_null($categories)
            || is_null($name)
            || is_null($price)
            || is_null($weight)
            || is_null($images)
            || is_null($description)
            || is_null($is_available)
            || is_null($is_admin_charge)
            || is_null($categories)
            || is_null($default_total_week)
            || empty($name)
            || empty($price)
            || empty($weight)
            || empty($images)
            || empty($description)
            || empty($categories)
            || empty($default_total_week)
            || !is_string($name)
            || !is_numeric($price)
            || !is_numeric($weight)
            || !is_numeric($default_total_week)
            || !is_string($images)
            || !is_string($description)
            || !is_bool($is_available)
            || !is_bool($is_admin_charge);

        if ($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add product, check the data you sent'
                ),
                400
            );
            return;
        }

        if (is_null($links)) {

            $links = "";
        }

        $result = $this->Binhusenstore_product->append_product($name, $categories, $price, $weight, $images, $description, $default_total_week, $is_available, $links, $is_admin_charge);

        if ($this->Binhusenstore_product->is_success === true) {

            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ),
                201
            );
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => $this->Binhusenstore_product->is_success
                ),
                500
            );
        }
    }

    public function get_products()
    {
        $req = Flight::request();
        $limit = $req->query->limit;
        $id_category = $req->query->id_category;
        $name_product = $req->query->name;

        $result = $this->Binhusenstore_product->get_products($limit, $id_category, $name_product);

        $is_exists = count($result) > 0;

        if ($this->Binhusenstore_product->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                ),
                200
            );
        } else if ($this->Binhusenstore_product->is_success !== true) {
            Flight::json(array(
                "success" => false,
                "message" => $this->Binhusenstore_product->is_success
            ), 500);
        } else {
            Flight::json(array(
                "success" => false,
                "message" => "Product not found"
            ), 404);
        }
    }

    public function get_product_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_product->get_product_by_id($id);

        $is_success = $this->Binhusenstore_product->is_success;

        $is_found = count($result) > 0;

        if ($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        } else if ($is_success !== null && !$is_found) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ),
                500
            );
            return;
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Product not found'
                ),
                404
            );
        }
    }

    public function remove_product($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_product->remove_product_by_id($id);

        $is_success = $this->Binhusenstore_product->is_success;

        if ($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete product success',
                )
            );
        } else if ($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ),
                500
            );
            return;
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Product not found'
                ),
                404
            );
        }
    }

    public function update_product_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $categories = $req->data->categories;
        $name = $req->data->name;
        $price = $req->data->price;
        $weight = $req->data->weight;
        $images = $req->data->images;
        $description = $req->data->description;
        $default_total_week = $req->data->default_total_week;
        $is_available = $req->data->is_available;
        $is_admin_charge = $req->data->is_admin_charge;
        $links = $req->data->links;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional categories
        $valid_links = !is_null($links) && is_string($links);
        if ($valid_links) $keyValueToUpdate["links"] = $links;

        // conditional categories
        $valid_categories = !is_null($categories) && is_string($categories) && !empty($categories);
        if ($valid_categories) $keyValueToUpdate["categories"] = $categories;

        // conditional $price
        $valid_price = !is_null($price) && is_numeric($price) && !empty($price);
        if ($valid_price) $keyValueToUpdate["price"] = $price;

        // conditional $weight
        $valid_weight = !is_null($weight) && is_numeric($weight) && !empty($weight);
        if ($valid_weight) $keyValueToUpdate["weight"] = $weight;

        // conditional $images
        $valid_images = !is_null($images) && is_string($images) && !empty($images);
        if ($valid_images) $keyValueToUpdate["images"] = $images;

        // conditional $description
        $valid_description = !is_null($description) && is_string($description) && !empty($description);
        if ($valid_description) $keyValueToUpdate["description"] = $description;

        // conditional $name
        $valid_name = !is_null($name) && is_string($name) && !empty($name);
        if ($valid_name) $keyValueToUpdate["name"] = $name;

        // conditional $default_total_week
        $valid_default_total_week = !is_null($default_total_week) && is_numeric($default_total_week) && !empty($default_total_week);
        if ($valid_default_total_week) $keyValueToUpdate["default_total_week"] = $default_total_week;

        // conditional $is_available
        $valid_is_available = !is_null($is_available) && is_bool($is_available);
        if ($valid_is_available) $keyValueToUpdate["is_available"] = $is_available;

        // conditional $is_admin_charge
        $valid_is_admin_charge = !is_null($is_admin_charge) && is_bool($is_admin_charge);
        if ($valid_is_admin_charge) $keyValueToUpdate["is_admin_charge"] = $is_admin_charge;

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if ($is_oke_to_update) {

            $result = $this->Binhusenstore_product->update_product_by_id($keyValueToUpdate, "id", $id);

            $is_success = $this->Binhusenstore_product->is_success;

            if ($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update product success',
                    )
                );
            } else if ($is_success !== true) {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ),
                    500
                );
                return;
            } else {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'Product not found'
                    ),
                    404
                );
            }
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update product, check the data you sent'
                )
            );
        }
    }

    public function get_products_for_landing_page()
    {
        $result = $this->Binhusenstore_product->get_products_landing_page();

        $is_success = $this->Binhusenstore_product->is_success;

        $is_found = count($result) > 0;

        if ($is_success === true && $is_found) {

            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        } else if ($is_success !== true) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ),
                500
            );
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Product not found'
                ),
                404
            );
        }
    }

    public function get_count_products()
    {

        $result = $this->Binhusenstore_product->count_products();

        if ($this->Binhusenstore_product->is_success === true && $result > 0) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                ),
                200
            );
        } else if ($this->Binhusenstore_product->is_success !== true) {

            Flight::json(array(
                "success" => false,
                "message" => $this->Binhusenstore_product->is_success
            ), 500);
        } else {
            Flight::json(array(
                "success" => false,
                "message" => "Product not found"
            ), 404);
        }
    }

    public function move_product_to_archive()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;

        $is_request_body_not_oke = empty($id) || is_null($id);

        if ($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to archive the product'
                ),
                400
            );
            return;
        }

        $move_product = $this->Binhusenstore_product->move_product_to_archive($id);
        $is_success = $move_product === true;
        if ($this->Binhusenstore_product->is_success === true && $is_success) {

            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Product archived'
                ),
                200
            );
        } else if ($is_success) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => "Produk sudah ada order, atau produk tidak ditemukan"
                ),
                400
            );
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => $this->Binhusenstore_product->is_success
                ),
                500
            );
        }
    }

    public function get_products_detail()
    {

        $result = $this->Binhusenstore_product->retrieve_products_and_detail();

        $is_exists = count($result) > 0;

        if ($this->Binhusenstore_product->is_success === true && $is_exists) {

            Flight::json(
                [
                    "success" => true,
                    "data" => $result
                ],
                200
            );
        } else if ($this->Binhusenstore_product->is_success !== true) {

            Flight::json(
                [
                    "success" => false,
                    "message" => $this->Binhusenstore_product->is_success
                ],
                500
            );
        } else {

            Flight::json(
                [
                    "success" => false,
                    "message" => "Product not found"
                ],
                404
            );
        }
    }
}
