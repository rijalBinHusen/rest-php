<?php
require_once(__DIR__ . '/product_archived_model.php');

class Binhusenstore_product_archived
{
    protected $Binhusenstore_product;
    function __construct()
    {
        $this->Binhusenstore_product = new Binhusenstore_product_archived_model();
    }

    public function get_products()
    {
        $req = Flight::request();
        $limit = $req->query->limit;
        $id_category = $req->query->id_category;
        $name_product = $req->query->name;

        $result = $this->Binhusenstore_product->get_products_archived($limit, $id_category, $name_product);

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

    public function move_product_from_archived($id)
    {
        // product_archived/8,the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_product->move_product_from_archived_by_id($id);

        $is_success = $this->Binhusenstore_product->is_success;

        $is_found = count($result) > 0;

        if ($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => "Product unarchived"
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
        $result = $this->Binhusenstore_product->remove_product_archived_by_id($id);

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
}
