<?php
require_once(__DIR__ . '/payments_model.php');
require_once(__DIR__ . '../../../../utils/piece/validator.php');

class Binhusenstore_payment
{
    protected $Binhusenstore_payment;
    function __construct()
    {
        $this->Binhusenstore_payment = new Binhusenstore_payment_model();
    }

    public function add_payment()
    {
        // request
        $req = Flight::request();
        $id_order = $req->data->id_order;
        $id_order_group = $req->data->id_order_group;
        $balance = $req->data->balance;
        $phone = $req->data->phone;
        $date_payment = $req->data->date_payment;

        $validator = new Validator();

        $what_s_to_check = array(
            "phone" => "number",
            "id_order" => "string",
            "balance" => "number",
            "date_payment" => "YMDate"
        );

        $result = null;
        $is_request_body_oke = $validator->check_type($req->data, $what_s_to_check);

        if (!$is_request_body_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add payment, check the data you sent'
                ),
                400
            );
            return;
        }

        $result = $this->Binhusenstore_payment->append_payment($id_order, $balance, $id_order_group, $phone, $date_payment);
        $is_success = $this->Binhusenstore_payment->is_success;
        if (is_string($result) && strlen($result) > 9) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => $result
                ),
                400
            );
        } else if ($is_success && strlen($result) === 9) {

            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ),
                201
            );
        } else if (count($result) == 0) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Order not found'
                ),
                404
            );
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => $this->Binhusenstore_payment->is_success
                ),
                500
            );
        }
    }

    public function get_payments()
    {
        // catch the query string request
        $req = Flight::request();
        $id_order = $req->query->id_order;

        $result = $this->Binhusenstore_payment->get_payments($id_order);

        $is_exists = count($result) > 0;

        if ($this->Binhusenstore_payment->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                ),
                200
            );
        } else if ($this->Binhusenstore_payment->is_success !== true) {
            Flight::json(
                array(
                    "success" => false,
                    "message" => $result
                ),
                500
            );
        } else {
            Flight::json(
                array(
                    "success" => false,
                    "message" => "Payments not found"
                ),
                404
            );
        }
    }

    public function get_payment_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_payment->get_payment_by_id($id);

        $is_success = $this->Binhusenstore_payment->is_success;

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
                    'message' => 'Payment not found'
                ),
                404
            );
        }
    }

    public function remove_payment($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_payment->remove_payment_by_id($id);

        $is_success = $this->Binhusenstore_payment->is_success;

        if ($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete payment success',
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
                    'message' => 'Payment not found'
                ),
                404
            );
        }
    }

    // public function update_payment_by_id($id)
    // {
    //     // catch the query string request
    //     $req = Flight::request();
    //     $date_payment = $req->data->date_payment;
    //     $id_order = $req->data->id_order;
    //     $balance = $req->data->balance;
    //     $is_paid = $req->data->is_paid;

    //     // initiate the column and values to update
    //     $keyValueToUpdate = array();

    //     // conditional $date_payment


    //     $result = null;

    //     $valid_date_payment = !is_null($date_payment);

    //     if ($valid_date_payment) {
    //         $validator = new Validator();
    //         $isDatePaymentValid = $validator->isYMDDate($date_payment);

    //         if($isDatePaymentValid) {

    //             $keyValueToUpdate["date_payment"] = $date_payment;
    //         }
    //     }

    //     // conditional $id_order
    //     $valid_id_order = !is_null($id_order) && is_string($id_order);
    //     if ($valid_id_order) {
    //         $keyValueToUpdate["id_order"] = $id_order;
    //     }

    //     // conditional $balance
    //     $valid_balance = !is_null($balance) && is_numeric($balance);
    //     if ($valid_balance) {
    //         $keyValueToUpdate["balance"] = $balance;
    //     }

    //     // conditional $is_paid
    //     $valid_is_paid = !is_null($is_paid) && is_bool($is_paid);
    //     if ($valid_is_paid) {
    //         $keyValueToUpdate["is_paid"] = $is_paid;
    //     }

    //     $is_oke_to_update = count($keyValueToUpdate) > 0;

    //     if($is_oke_to_update) {

    //         $result = $this->Binhusenstore_payment->update_payment_by_id($keyValueToUpdate, "id", $id);

    //         $is_success = $this->Binhusenstore_payment->is_success;

    //         if($is_success === true && $result > 0) {

    //             Flight::json(
    //                 array(
    //                     'success' => true,
    //                     'message' => 'Update payment success',
    //                 )
    //             );
    //         }

    //         else if($is_success !== true) {

    //             Flight::json(
    //                 array(
    //                     'success' => false,
    //                     'message' => $is_success
    //                 ), 500
    //             );
    //             return;
    //         }

    //         else {

    //             Flight::json(
    //                 array(
    //                     'success' => false,
    //                     'message' => 'Payment not found'
    //                 ), 404
    //             );
    //         }
    //     } 

    //     else {

    //         Flight::json(
    //             array(
    //                 'success' => false,
    //                 'message' => 'Failed to update payment, check the data you sent'
    //             )
    //         );
    //     }
    // }

    // public function mark_payment_as_paid()
    // {
    //     // request
    //     $req = Flight::request();
    //     $date_paid = $req->data->date_paid;
    //     $id_order = $req->data->id_order;
    //     $phone = $req->data->phone;
    //     $balance = $req->data->balance;

    //     $validator = new Validator();

    //     $result = null;
    //     $isDatePaymentValid = $validator->isYMDDate($date_paid);

    //     $is_request_body_oke = !is_null($date_paid)
    //         && $isDatePaymentValid
    //         && !is_null($phone)
    //         && is_numeric($phone)
    //         && !is_null($balance)
    //         && is_numeric($balance);

    //     if (!$is_request_body_oke) {

    //         Flight::json([
    //             'success' => false,
    //             'message' => 'Failed to update payment, check the data you sent'
    //         ], 400);
    //         return;
    //     }

    //     $result = $this->Binhusenstore_payment->mark_payment_as_paid_by_id_order_or_id_group($id_order, $date_paid, $balance, $phone);

    //     $is_success = $this->Binhusenstore_payment->is_success;

    //     if ($is_success === true && $result === true) {

    //         Flight::json([
    //             'success' => true,
    //             'message' => 'Update payment success'
    //         ]);
    //     } else if ($result === 0) {

    //         Flight::json([
    //             'success' => false,
    //             'message' => 'Payment not found'
    //         ], 404);
    //     } else if ($is_success !== true) {

    //         Flight::json([
    //             'success' => false,
    //             'message' => $is_success
    //         ], 500);
    //     } else {

    //         Flight::json([
    //             'success' => false,
    //             'message' => $result
    //         ], 400);
    //     }
    // }

    public function get_sum_balance_payments()
    {
        $result = $this->Binhusenstore_payment->sum_balance();

        $is_success = $this->Binhusenstore_payment->is_success;

        if ($is_success === true) {

            Flight::json([
                'success' => true,
                'total_balance' => $result
            ]);
        } else {

            Flight::json([
                'success' => false,
                'message' => $is_success
            ], 500);
        }
    }

    public function get_payment_group_by_id_order()
    {

        $req = Flight::request();
        $limit = $req->query->limit;

        $result = $this->Binhusenstore_payment->retrieve_payment_group_by_id_order($limit);

        $is_success = $this->Binhusenstore_payment->is_success;

        $is_found = count($result) > 0;

        if ($is_success === true && $is_found) {

            Flight::json([
                'success' => true,
                'data' => $result
            ]);
        } else if ($is_success !== true) {

            Flight::json([
                'success' => false,
                'message' => $is_success
            ], 500);
        } else {

            Flight::json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }
    }
}
