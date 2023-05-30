<?php
require_once(__DIR__ . '/base_clock_model.php');

class My_report_base_clock
{
    protected $my_report_base_clock;
    function __construct()
    {
        $this->my_report_base_clock = new My_report_base_clock_model();
    }

    public function add_base_clock()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $parent = $req->data->parent;
        $shift = $req->data->shift;
        $no_do = $req->data->no_do;
        $reg = $req->data->reg;
        $start = $req->data->start;
        $finish = $req->data->finish;
        $rehat = $req->data->rehat;

        $result = null;

        $valid_request_body =   !is_null($parent) 
                                && !is_null($shift) 
                                && !is_null($no_do) 
                                && !is_null($reg) 
                                && !is_null($start) 
                                && !is_null($finish) 
                                && !is_null($rehat);
        if($valid_request_body) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_base_clock->write_base_clock($id, $parent, $shift, $no_do, $reg, $start, $finish, $rehat);
            } else {
                // append warehouse
                $result = $this->my_report_base_clock->append_base_clock($parent, $shift, $no_do, $reg, $start, $finish, $rehat);
            }

            if($this->my_report_base_clock->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_base_clock->is_success
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
                'message' => 'Failed to add base clock, check the data you sent'
            ), 400
        );
    }

    public function get_base_clock_by_parent()
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

        $result = $this->my_report_base_clock->get_base_clock_by_parent($parent);
        
        $is_exists = count($result) > 0;

        if($this->my_report_base_clock->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_base_clock->is_success !== true) {

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
                    "message" => "Base clock not found"
                )
            , 404);
        }

    }

    public function remove_base_clock_by_parent()
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

        $result = $this->my_report_base_clock->remove_base_clock_by_parent($parent);
        
        $is_exists = count($result) > 0;

        if($this->my_report_base_clock->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "message" => "Delete base clock success"
                    )
            , 200);

        }

        else if ($this->my_report_base_clock->is_success !== true) {

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
                    "message" => "Base clock not found"
                )
            , 404);
        }

    }

    public function get_base_clock_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_clock->get_base_clock_by_id($id);

        $is_success = $this->my_report_base_clock->is_success;

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
                    'message' => 'Base clock not found'
                )
            );
        }
    }
    
    public function update_base_clock_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $parent = $req->data->parent;
        $shift = $req->data->shift;
        $no_do = $req->data->no_do;
        $reg = $req->data->reg;
        $start = $req->data->start;
        $finish = $req->data->finish;
        $rehat = $req->data->rehat;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $parent
        $valid_parent = !is_null($parent) && !empty($parent);
        if ($valid_parent) {
            $keyValueToUpdate["parent"] = $parent;
        }

        // conditional shift
        $valid_shift = !is_null($shift) && !empty($shift);
        if ($valid_shift) {
            $keyValueToUpdate["shift"] = $shift;
        }

        // conditional $no_do
        $valid_no_do = !is_null($no_do) && !empty($no_do);
        if ($valid_no_do) {
            $keyValueToUpdate["no_do"] = $no_do;
        }

        // conditional $reg
        $valid_reg = !is_null($reg) && !empty($reg);
        if ($valid_reg) {
            $keyValueToUpdate["reg"] = $reg;
        }

        // conditional $start
        $valid_start = !is_null($start) && !empty($start);
        if ($valid_start) {
            $keyValueToUpdate["start"] = $start;
        }

        // conditional $finish
        $finish = !is_null($finish) && !empty($finish);
        if ($finish) {
            $keyValueToUpdate["finish"] = $finish;
        }

        // conditional $rehat
        $valid_rehat = !is_null($rehat) && !empty($rehat);
        if ($valid_rehat) {
            $keyValueToUpdate["rehat"] = $rehat;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_base_clock->update_base_clock_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_base_clock->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update base clock success',
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
                        'message' => 'Base clock not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update base clock, check the data you sent'
                )
            );
        }

        
    }

    public function remove_base_clock($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_clock->remove_base_clock($id);

        $is_success = $this->my_report_base_clock->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete base clock success',
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
                    'message' => 'Base clock not found'
                )
            );
        }
    }

}
