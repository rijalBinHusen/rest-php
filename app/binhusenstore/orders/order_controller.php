<?php
require_once(__DIR__ . '/order_model.php');

class Binhusenstore_order
{
    protected $Binhusenstore_order;
    function __construct()
    {
        $this->Binhusenstore_order = new Binhusenstore_order_model();
    }

    public function add_order()
    {
        // request
        $req = Flight::request();
        $date_order = $req->data->date_order;
        $id_group = $req->data->id_group;
        $is_group = $req->data->is_group;
        $id_product = $req->data->id_product;
        $name_of_customer = $req->data->name_of_customer;
        $sent = $req->data->sent;
        $title = $req->data->title;
        $total_balance = $req->data->total_balance;
        $phone = $req->data->phone;
        $admin_charge = $req->data->admin_charge;

        $result = null;

        $is_request_body_not_oke = is_null($date_order)
            || !is_string($date_order)
            || is_null($id_group)
            || !is_string($id_group)
            || is_null($is_group)
            || !is_bool($is_group)
            || is_null($id_product)
            || !is_string($id_product)
            || is_null($name_of_customer)
            || !is_string($name_of_customer)
            || is_null($sent)
            || !is_string($sent)
            || is_null($title)
            || !is_string($title)
            || is_null($total_balance)
            || !is_numeric($total_balance)
            || is_null($phone)
            || !is_numeric($phone)
            || is_null($admin_charge)
            || !is_bool($admin_charge);

        if ($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add order, check the data you sent'
                ),
                400
            );
            return;
        }

        $result = $this->Binhusenstore_order->append_order($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance, $phone, $admin_charge);

        if ($this->Binhusenstore_order->is_success === true) {

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
                    'message' => $this->Binhusenstore_order->is_success
                ),
                500
            );
        }
    }

    public function add_order_and_payment()
    {
        // request
        $req = Flight::request();
        $date_order = $req->data->date_order;
        $id_group = $req->data->id_group;
        $is_group = $req->data->is_group;
        $id_product = $req->data->id_product;
        $name_of_customer = $req->data->name_of_customer;
        $sent = $req->data->sent;
        $title = $req->data->title;
        $total_balance = $req->data->total_balance;
        $phone = $req->data->phone;
        $admin_charge = $req->data->admin_charge;
        $start_date_payment = $req->data->start_date_payment;
        $balance_payment = $req->data->balance_payment;
        $end_date_payment = $req->data->end_date_payment;

        $result = null;

        $is_request_body_not_oke = is_null($date_order)
            || is_null($start_date_payment)
            || is_null($end_date_payment)
            || is_null($id_group)
            || !is_string($id_group)
            || is_null($is_group)
            || !is_bool($is_group)
            || is_null($id_product)
            || !is_string($id_product)
            || is_null($name_of_customer)
            || !is_string($name_of_customer)
            || is_null($sent)
            || !is_string($sent)
            || is_null($title)
            || !is_string($title)
            || is_null($total_balance)
            || !is_numeric($total_balance)
            || is_null($balance_payment)
            || !is_numeric($balance_payment)
            || is_null($phone)
            || !is_numeric($phone)
            || is_null($admin_charge)
            || !is_bool($admin_charge);

        if ($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add order, check the data you sent'
                ),
                400
            );
            return;
        }

        $result = $this->Binhusenstore_order->append_order_and_payment($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance, $phone, $admin_charge, $start_date_payment, $balance_payment, $end_date_payment);

        if ($this->Binhusenstore_order->is_success === true) {

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
                    'message' => $this->Binhusenstore_order->is_success
                ),
                500
            );
        }
    }

    public function get_orders()
    {

        $req = Flight::request();
        $limit = $req->query->limit;

        $result = $this->Binhusenstore_order->get_orders($limit);

        $is_exists = count($result) > 0;

        if ($this->Binhusenstore_order->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                ),
                200
            );
        } else if ($this->Binhusenstore_order->is_success !== true) {
            Flight::json(array(
                "success" => false,
                "message" => $result
            ), 500);
        } else {
            Flight::json(array(
                "success" => false,
                "message" => "Order not found"
            ), 404);
        }
    }

    public function get_order_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_order->get_order_by_id($id);

        $is_success = $this->Binhusenstore_order->is_success;

        $is_found = count($result) > 0;

        if ($is_success === true && $is_found) {

            Flight::json(
                [
                    'success' => true,
                    'data' => $result
                ]
            );
        } else if ($is_success !== true) {

            Flight::json(
                [
                    'success' => false,
                    'message' => $is_success
                ],
                500
            );
        } else {
            Flight::json(
                [
                    'success' => false,
                    'message' => 'Order not found'
                ],
                404
            );
        }
    }

    public function get_phone_by_order_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_order->phone_by_order_id($id);

        $is_success = $this->Binhusenstore_order->is_success;

        $is_found = $result !== false;

        if ($is_success === true && $is_found) {

            Flight::json(
                [
                    'success' => true,
                    'data' => $result
                ]
            );
        } else if ($is_success !== true) {

            Flight::json(
                [
                    'success' => false,
                    'message' => $is_success
                ],
                500
            );
        } else {
            Flight::json(
                [
                    'success' => false,
                    'message' => 'Order not found'
                ],
                404
            );
        }
    }

    public function remove_order($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_order->remove_order_by_id($id);

        $is_success = $this->Binhusenstore_order->is_success;

        if ($is_success === true && $result > 0) {
            Flight::json(
                [
                    'success' => true,
                    'message' => 'Delete order success',
                ]
            );
        } else if ($is_success !== true) {
            Flight::json(
                [
                    'success' => false,
                    'message' => $is_success
                ],
                500
            );
            return;
        } else {
            Flight::json(
                [
                    'success' => false,
                    'message' => 'Order not found'
                ],
                404
            );
        }
    }

    public function update_order_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $date_order = $req->data->date_order;
        $id_group = $req->data->id_group;
        $is_group = $req->data->is_group;
        $id_product = $req->data->id_product;
        $name_of_customer = $req->data->name_of_customer;
        $sent = $req->data->sent;
        $title = $req->data->title;
        $total_balance = $req->data->total_balance;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $date_order
        $valid_date_order = !is_null($date_order) && is_string($date_order);
        if ($valid_date_order) $keyValueToUpdate["date_order"] = $date_order;

        // conditional $id_group
        $valid_id_group = !is_null($id_group) && is_string($id_group) && strlen($id_group) === 9;
        if ($valid_id_group) $keyValueToUpdate["id_group"] = $id_group;

        // conditional $is_group
        $valid_is_group = !is_null($is_group) && is_bool($is_group);
        if ($valid_is_group) $keyValueToUpdate["is_group"] = $is_group;

        // conditional $id_product
        $valid_id_product = !is_null($id_product)  && is_string($id_product) && strlen($id_product) === 9;
        if ($valid_id_product) $keyValueToUpdate["id_product"] = $id_product;

        // conditional $name_of_customer
        $valid_name_of_customer = !is_null($name_of_customer) && is_string($name_of_customer);
        if ($valid_name_of_customer) $keyValueToUpdate["name_of_customer"] = $name_of_customer;

        // conditional $sent
        $valid_sent = !is_null($sent) && is_string($sent);
        if ($valid_sent) $keyValueToUpdate["sent"] = $sent;

        // conditional $title
        $valid_title = !is_null($title) && is_string($title);
        if ($valid_title) $keyValueToUpdate["title"] = $title;

        // conditional $total_balance
        $valid_total_balance = !is_null($total_balance) && is_numeric($total_balance);
        if ($valid_total_balance) $keyValueToUpdate["total_balance"] = $total_balance;

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if ($is_oke_to_update) {

            $result = $this->Binhusenstore_order->update_order_by_id($keyValueToUpdate, "id", $id);

            $is_success = $this->Binhusenstore_order->is_success;

            if ($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update order success',
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
                        'message' => 'Order not found'
                    ),
                    404
                );
            }
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update order, check the data you sent'
                ),
                400
            );
        }
    }

    public function get_count_orders()
    {

        $result = $this->Binhusenstore_order->count_orders();

        if ($this->Binhusenstore_order->is_success === true) {

            Flight::json([
                "success" => true,
                "data" => $result
            ], 200);
        } else if ($this->Binhusenstore_order->is_success !== true) {

            Flight::json([
                "success" => false,
                "message" => $this->Binhusenstore_order->is_success
            ], 500);
        } else {

            Flight::json([
                "success" => false,
                "message" => "Order not found"
            ], 404);
        }
    }

    public function move_order_to_archive_by_order_id()
    {
        $req = Flight::request();
        $id_order = $req->data->id_order;
        $phone = $req->data->phone;

        $is_id_order_not_oke = empty($id_order) || is_null($id_order) || strlen($id_order) !== 9;
        $is_phone_not_oke = empty($phone) || is_null($phone) || !is_numeric($phone);

        $is_request_body_not_oke = $is_id_order_not_oke || $is_phone_not_oke;

        if ($is_request_body_not_oke) {

            Flight::json([
                'success' => false,
                'message' => "Failed to archive order, check the data you sent"
            ], 400);
            return;
        }

        $result = $this->Binhusenstore_order->move_order_to_archive($id_order, $phone);

        if ($result === true) {

            Flight::json([
                "success" => true,
                "message" => "Order archived"
            ], 201);
        }
        // 
        else if ($result === 0) {

            Flight::json([
                "success" => false,
                "message" => "Order not found"
            ], 404);
        }
        // phone not matched
        else if ($result !== true && is_string($result)) {

            Flight::json([
                'success' => false,
                'message' => $result
            ], 400);
        }
        // 
        else {

            Flight::json([
                "success" => false,
                "message" => $this->Binhusenstore_order->is_success
            ], 500);
        }
    }

    public function is_order_able_to_cancel()
    {

        $req = Flight::request();
        $id_order = $req->data->id_order;
        $phone = $req->data->phone;

        $result = false;
        $is_id_order_valid = strlen($id_order) === 9;

        if ($is_id_order_valid) {

            $result = $this->Binhusenstore_order->phone_by_order_id($id_order);
        }


        $is_success = $this->Binhusenstore_order->is_success;
        $is_found = $result !== false;
        $is_phone_matched = $result == $phone;

        if ($is_success === true && $is_phone_matched) {

            Flight::json(
                [
                    'success' => true,
                    'message' => $is_phone_matched
                ]
            );
        } else if ($is_success !== true) {

            Flight::json(
                [
                    'success' => false,
                    'message' => $is_success
                ],
                500
            );
        } else {
            Flight::json(
                [
                    'success' => false,
                    'message' => "Id order atau nomor telfon tidak cocok",
                ],
                404
            );
        }
    }

    public function merge_order()
    {
        // catch the query string request
        $error_message = "check the data you sent!";

        $req = Flight::request();
        $id_order_1 = $req->data->id_order_1;
        $id_order_2 = $req->data->id_order_2;

        $is_id_order_not_same = $id_order_1 !== $id_order_2;
        $is_id_order_oke = strlen($id_order_1) === 9 && strlen($id_order_2) === 9 && $is_id_order_not_same;

        if (!$is_id_order_not_same) {
            $error_message = "The id order can't be same!";
        }

        if ($is_id_order_oke) {

            $result = $this->Binhusenstore_order->merge_order_as_group($id_order_1, $id_order_2);

            $is_success = $this->Binhusenstore_order->is_success;

            if ($result === 0) {

                Flight::json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            // 
            else if ($is_success === true && is_numeric($result) && $result > 0) {

                Flight::json([
                    'success' => true,
                    'message' => 'Order merged',
                ]);
            }
            // 
            else if ($is_success !== true) {

                Flight::json([
                    'success' => false,
                    'message' => $is_success
                ], 500);
            }
            // 
            else {

                Flight::json([
                    'success' => false,
                    'message' => $result
                ], 400);
            }
        }
        // 
        else {

            Flight::json([
                'success' => false,
                'message' => 'Failed to merge order, ' . $error_message
            ], 400);
        }
    }

    public function unmerge_order()
    {
        // catch the query string request
        $error_message = "Check the data you sent!";

        $req = Flight::request();
        $id_group = $req->data->id_group;
        $phone = $req->data->phone;

        $is_id_group_oke = strlen($id_group) === 9;
        $is_phone_oke = strlen($phone) >= 10;
        if ($is_id_group_oke && $is_phone_oke) {

            $result = $this->Binhusenstore_order->unmerge_order_group($id_group, $phone);

            $is_success = $this->Binhusenstore_order->is_success;

            if ($result === 0) {

                Flight::json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            // 
            else if ($is_success === true && is_numeric($result) && $result > 0) {

                Flight::json([
                    'success' => true,
                    'message' => 'Order unmerged',
                ]);
            }
            // 
            else if ($is_success !== true) {

                Flight::json([
                    'success' => false,
                    'message' => $is_success
                ], 500);
            }
            // 
            else {

                Flight::json([
                    'success' => false,
                    'message' => $result
                ], 400);
            }
        }
        // 
        else {

            Flight::json([
                'success' => false,
                'message' => 'Failed to merge order, ' . $error_message
            ], 400);
        }
    }
}
