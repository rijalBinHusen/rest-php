<?php
require_once(__DIR__ . '/order_model.php.php');

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

        $result = null;

        $is_request_body_not_oke = is_null($date_order)
                                    || is_null($id_group)
                                    || is_null($is_group)
                                    || is_null($id_product)
                                    || is_null($name_of_customer)
                                    || is_null($sent)
                                    || is_null($title)
                                    || is_null($total_balance);

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add order, check the data you sent'
                ), 400
            );
            return;
        }

        $result = $this->Binhusenstore_order->append_order($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance);

        if($this->Binhusenstore_order->is_success === true) {
        
            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ), 201
            );
        } 
        
        else {
            
            Flight::json(
                array(
                    'success'=> false,
                    'message'=> $this->Binhusenstore_order->is_success
                ), 500
            );
        }
    }
    
    public function get_orders()
    {

        $result = $this->Binhusenstore_order->get_orders();
                
        $is_exists = count($result) > 0;

        if($this->Binhusenstore_order->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->Binhusenstore_order->is_success !== true) {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {
            Flight::json( array(
            "success" => false,
            "message" => "order not found"
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

        if($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'order not found'
                ), 404
            );
        }
    }

    public function remove_order($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_order->remove_order_by_id($id);

        $is_success = $this->Binhusenstore_order->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete order success',
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'order not found'
                ), 404
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
        $valid_date_order = !is_null($date_order);
        if ($valid_date_order) {
            $keyValueToUpdate["date_order"] = $date_order;
        }

        // conditional $id_group
        $valid_id_group = !is_null($id_group);
        if ($valid_id_group) {
            $keyValueToUpdate["id_group"] = $id_group;
        }

        // conditional $is_group
        $valid_is_group = !is_null($is_group);
        if ($valid_is_group) {
            $keyValueToUpdate["is_group"] = $is_group;
        }

        // conditional $id_product
        $valid_id_product = !is_null($id_product);
        if ($valid_id_product) {
            $keyValueToUpdate["id_product"] = $id_product;
        }

        // conditional $name_of_customer
        $valid_name_of_customer = !is_null($name_of_customer);
        if ($valid_name_of_customer) {
            $keyValueToUpdate["name_of_customer"] = $name_of_customer;
        }

        // conditional $sent
        $valid_sent = !is_null($sent);
        if ($valid_sent) {
            $keyValueToUpdate["sent"] = $sent;
        }

        // conditional $title
        $valid_title = !is_null($title);
        if ($valid_title) {
            $keyValueToUpdate["title"] = $title;
        }

        // conditional $total_balance
        $valid_total_balance = !is_null($total_balance);
        if ($valid_total_balance) {
            $keyValueToUpdate["total_balance"] = $total_balance;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->Binhusenstore_order->update_order_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->Binhusenstore_order->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update order success',
                    )
                );
            }
    
            else if($is_success !== true) {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ), 500
                );
                return;
            }
    
            else {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'order not found'
                    ), 404
                );
            }
        } 
        
        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update order, check the data you sent'
                )
            );
        }
    }
}
