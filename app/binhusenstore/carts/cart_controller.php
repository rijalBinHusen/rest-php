<?php
require_once(__DIR__ . '/cart_model.php.php');

class Binhusenstore_cart
{
    protected $Binhusenstore_cart;
    function __construct()
    {
        $this->Binhusenstore_cart = new Binhusenstore_cart_model();
    }

    public function add_cart()
    {
        // request
        $req = Flight::request();
        $id_user = $req->data->id_user;
        $product_id = $req->data->product_id;
        $qty = $req->data->qty;

        $result = null;

        $is_request_body_not_oke = is_null($product_id)
            || is_null($id_user)
            || is_null($qty);

        if ($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add cart, check the data you sent'
                ),
                400
            );
            return;
        }

        $result = $this->Binhusenstore_cart->append_cart($id_user, $product_id, $qty);

        if ($this->Binhusenstore_cart->is_success === true) {

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
                    'message' => $this->Binhusenstore_cart->is_success
                ),
                500
            );
        }
    }

    public function get_carts()
    {
        $id_user = Flight::request()->query->id_user;

        $result = array();
        if ($id_user) {

            $result = $this->Binhusenstore_cart->get_carts($id_user);
        }


        $is_exists = count($result) > 0;

        if ($this->Binhusenstore_cart->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                ),
                200
            );
        } else if ($this->Binhusenstore_cart->is_success !== true) {
            Flight::json(array(
                "success" => false,
                "message" => $result
            ), 500);
        } else {
            Flight::json(array(
                "success" => false,
                "message" => "Cart not found"
            ), 404);
        }
    }

    public function get_cart_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_cart->get_cart_by_id($id);

        $is_success = $this->Binhusenstore_cart->is_success;

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
            return;
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Cart not found'
                ),
                404
            );
        }
    }

    public function remove_cart($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_cart->remove_cart_by_id($id);

        $is_success = $this->Binhusenstore_cart->is_success;

        if ($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete cart success',
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
                    'message' => 'Cart not found'
                ),
                404
            );
        }
    }

    public function update_cart_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $qty = $req->data->qty;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $qty
        $valid_qty = !is_null($qty);
        if ($valid_qty) {
            $keyValueToUpdate["qty"] = $qty;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if ($is_oke_to_update) {

            $result = $this->Binhusenstore_cart->update_cart_by_id($keyValueToUpdate, "id", $id);

            $is_success = $this->Binhusenstore_cart->is_success;

            if ($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update cart success',
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
                        'message' => 'Cart not found'
                    ),
                    404
                );
            }
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update Product, check the data you sent'
                )
            );
        }
    }
}
