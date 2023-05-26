<?php
require_once(__DIR__ . '/base_stock_model.php');

class My_report_base_stock
{
    protected $my_report_base_stock;
    function __construct()
    {
        $this->my_report_base_stock = new My_report_base_stock_model();
    }

    public function add_base_stock()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $parent = $req->data->parent;
        $shift = $req->data->shift;
        $item = $req->data->item;
        $awal = $req->data->awal;
        $in_stock = $req->data->in_stock;
        $out_stock = $req->data->out_stock;
        $date_in = $req->data->date_in;
        $plan_out = $req->data->out_stock;
        $date_out = $req->data->date_out;
        $date_end = $req->data->date_end;
        $real_stock = $req->data->real_stock;
        $problem = $req->data->problem;

        $result = null;

        $valid_request_body = !is_null($shift) 
                                && !is_null($item) 
                                && !is_null($awal) 
                                && !is_null($in_stock) 
                                && !is_null($out_stock) 
                                && !is_null($date_in) 
                                && !is_null($plan_out) 
                                && !is_null($date_out) 
                                && !is_null($date_end) 
                                && !is_null($real_stock) 
                                && !is_null($problem);

        if($valid_request_body) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_base_stock->write_base_stock($id, $parent, $shift, $item, $awal, $in_stock, $out_stock, $date_in, $plan_out, $date_out, $date_end, $real_stock, $problem);
            } else {
                // append warehouse
                $result = $this->my_report_base_stock->append_base_stock($parent, $shift, $item, $awal, $in_stock, $out_stock, $date_in, $plan_out, $date_out, $date_end, $real_stock, $problem);
            }

            if($this->my_report_base_stock->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_base_stock->is_success
                    ), 500
                );
                return;
            }
            
            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ), 201
            );
            return;
        }

        Flight::json(
            array(
                'success' => false,
                'message' => 'Failed to add base stock, check the data you sent'
            ), 400
        );
    }

    public function get_base_stock_by_parent()
    { 
        $request = Flight::request();
        $parent = $request->query->parent;

        $not_valid_query_string = is_null($parent) || empty($parent);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_base_stock->get_base_stock_by_parent($parent);
        
        $is_exists = count($result) > 0;

        if($this->my_report_base_stock->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_base_stock->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Base stock not found"
                )
            , 404);
        }

    }

    public function remove_base_stock_by_parent()
    { 
        $request = Flight::request();
        $parent = $request->query->parent;

        $not_valid_query_string = is_null($parent) || empty($parent);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_base_stock->remove_base_stock_by_parent($parent);
        
        $is_exists = count($result) > 0;

        if($this->my_report_base_stock->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_base_stock->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Base stock not found"
                )
            , 404);
        }

    }

    public function get_base_stock_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_stock->get_base_stock_by_id($id);

        $is_success = $this->my_report_base_stock->is_success;

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
                    'message' => 'Base stock not found'
                )
            );
        }
    }
    
    public function update_base_stock_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $shift = $req->data->shift;
        $parent = $req->data->parent;
        $item = $req->data->item;
        $awal = $req->data->awal;
        $in_stock = $req->data->in_stock;
        $out_stock = $req->data->out_stock;
        $date_in = $req->data->date_in;
        $plan_out = $req->data->plan_out;
        $date_out = $req->data->date_out;
        $date_end = $req->data->date_end;
        $real_stock = $req->data->real_stock;
        $problem = $req->data->problem;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional shift
        $valid_shift = !is_null($shift) && !empty($shift);
        if ($valid_shift) {
            $keyValueToUpdate["shift"] = $shift;
        }

        // conditional $item
        $valid_item = !is_null($item) && !empty($item);
        if ($valid_item) {
            $keyValueToUpdate["item"] = $item;
        }

        // conditional $awal
        $valid_awal = !is_null($awal) && !empty($awal);
        if ($valid_awal) {
            $keyValueToUpdate["awal"] = $awal;
        }

        // conditional $in_stock
        $valid_in_stock = !is_null($in_stock) && !empty($in_stock);
        if ($valid_in_stock) {
            $keyValueToUpdate["in_stock"] = $in_stock;
        }

        // conditional $out_stock
        $out_stock = !is_null($out_stock) && !empty($out_stock);
        if ($out_stock) {
            $keyValueToUpdate["out_stock"] = $out_stock;
        }

        // conditional $parent
        $valid_parent = !is_null($parent) && !empty($parent);
        if ($valid_parent) {
            $keyValueToUpdate["parent"] = $parent;
        }

        // conditional $date_in
        $valid_date_in = !is_null($date_in) && !empty($date_in);
        if ($valid_date_in) {
            $keyValueToUpdate["date_in"] = $date_in;
        }

        // conditional $plan_out
        $valid_plan_out = !is_null($plan_out) && !empty($plan_out);
        if ($valid_plan_out) {
            $keyValueToUpdate["plan_out"] = $plan_out;
        }

        // conditional $date_out
        $valid_date_out = !is_null($date_out) && !empty($date_out);
        if ($valid_date_out) {
            $keyValueToUpdate["date_out"] = $date_out;
        }

        // conditional $date_end
        $valid_date_end = !is_null($date_end) && !empty($date_end);
        if ($valid_date_end) {
            $keyValueToUpdate["date_end"] = $date_end;
        }

        // conditional $real_stock
        $valid_real_stock = !is_null($real_stock) && !empty($real_stock);
        if ($valid_real_stock) {
            $keyValueToUpdate["real_stock"] = $real_stock;
        }

        // conditional $problem
        $valid_problem = !is_null($problem) && !empty($problem);
        if ($valid_problem) {
            $keyValueToUpdate["problem"] = $problem;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_base_stock->update_base_stock_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_base_stock->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update base stock success',
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
                        'message' => 'Base stock not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update base stock, check the data you sent'
                )
            );
        }

        
    }

    public function remove_base_stock($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_stock->remove_base_stock($id);

        $is_success = $this->my_report_base_stock->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete base stock success',
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
                    'message' => 'Base stock not found'
                )
            );
        }
    }

}
