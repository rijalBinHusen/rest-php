<?php
require_once(__DIR__ . './supervisor_model.php');

class My_report_supervisor
{
    protected $my_report_supervisor;
    function __construct()
    {
        $this->my_report_supervisor = new My_report_supervisor_model();
    }
    public function get_supervisors()
    { 
        $result = $this->my_report_supervisor->get_supervisors();
        
        if($this->my_report_supervisor->is_success) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }
        
        else {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }

    }
    public function add_warehouse()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        $supervisor_warehouse = $req->data->supervisor_warehouse;
        $supervisor_shift = $req->data->supervisor_shift;
        $is_disabled = $req->data->is_disabled;

        $result = null;

        if($supervisor_name && $supervisor_phone && $supervisor_warehouse && $supervisor_shift && $is_disabled) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_supervisor->write_supervisor($id, $supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled);
            } else {
                // append warehouse
                $result = $this->my_report_supervisor->append_supervisor($supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled);
            }

            if($this->my_report_supervisor->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_supervisor->is_success
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
                'message' => 'Failed add supervisor, check the data you sent'
            ), 400
        );
    }
    public function get_warehouse_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_supervisor->get_supervisor_by_id($id);

        $is_success = $this->my_report_supervisor->is_success;

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
                    'message' => 'Supervisor not found'
                )
            );
        }
    }

    // // public function deleteGuest($id) {
    // //     // myguest/8
    // //     // the 8 will automatically becoming parameter $id
    // //     return $this->my_report_supervisor->deleteGuest($id);
    // // }

    public function update_supervisor_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        $supervisor_warehouse = $req->data->supervisor_warehouse;
        $supervisor_shift = $req->data->supervisor_shift;
        $is_disabled = $req->data->is_disabled;

        $invalid_request_body = is_null($supervisor_name) || is_null($supervisor_phone) || is_null($id) || is_null($supervisor_warehouse) || is_null($supervisor_shift) || is_null($is_disabled);

        if($invalid_request_body) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update supervisor, check the data you sent'
                )
            );
            return;
        }

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional supervisor_name
        if ($supervisor_name) {
            $keyValueToUpdate["supervisor_name"] = $supervisor_name;
        }

        // conditional $supervisor_phone
        if ($supervisor_phone) {
            $keyValueToUpdate["supervisor_phone"] = $supervisor_phone;
        }

        // conditional $supervisor_warehouse
        if ($supervisor_warehouse) {
            $keyValueToUpdate["supervisor_warehouse"] = $supervisor_warehouse;
        }

        // conditional $supervisor_shift
        if ($supervisor_shift) {
            $keyValueToUpdate["supervisor_shift"] = $supervisor_shift;
        }

        // conditional $is_disabled
        if ($is_disabled) {
            $keyValueToUpdate["is_disabled"] = $is_disabled;
        }

        $this->my_report_supervisor->update_supervisor_by_id($keyValueToUpdate, "id", $id);

        $is_success = $this->my_report_supervisor->is_success;

        if($is_success === true) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Update warehouse success'
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
                    'message' => 'Warehouse not found'
                )
            );
        }
        
    }
}
