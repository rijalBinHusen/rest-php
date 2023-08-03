<?php
require_once(__DIR__ . '/supervisor_model.php');

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
    public function add_supervisor()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        $supervisor_shift = $req->data->supervisor_shift;
        $supervisor_warehouse = $req->data->supervisor_warehouse;
        $is_disabled = $req->data->is_disabled;

        $is_request_body_oke = !is_null($supervisor_name) 
                                && !is_null($supervisor_phone) 
                                && !is_null($supervisor_warehouse) 
                                && !is_null($supervisor_shift) 
                                && !is_null($is_disabled);        

        $result = null;

        if(!$is_request_body_oke) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed add supervisor, check the data you sent'
                ), 400
            );
        }

        else {
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
        }
    }
    public function get_supervisor_by_id($id)
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
                ), 404
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

        
        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional supervisor_name
        $valid_supervisor_name = !is_null($supervisor_name) && !empty($supervisor_name);
        if ($valid_supervisor_name) {
            $keyValueToUpdate["supervisor_name"] = $supervisor_name;
        }

        // conditional $supervisor_phone
        $valid_supervisor_phone = !is_null($supervisor_phone) && !empty($supervisor_phone);
        if ($valid_supervisor_phone) {
            $keyValueToUpdate["supervisor_phone"] = $supervisor_phone;
        }

        // conditional $supervisor_warehouse
        $valid_supervisor_warehouse = !is_null($supervisor_warehouse) && !empty($supervisor_warehouse);
        if ($valid_supervisor_warehouse) {
            $keyValueToUpdate["supervisor_warehouse"] = $supervisor_warehouse;
        }

        // conditional $supervisor_shift
        $valid_supervisor_shift = !is_null($supervisor_shift) && !empty($supervisor_shift);
        if ($valid_supervisor_shift) {
            $keyValueToUpdate["supervisor_shift"] = $supervisor_shift;
        }

        // conditional $is_disabled
        $valid_is_disabled = !is_null($is_disabled) && !empty($is_disabled);
        if ($valid_is_disabled) {
            $keyValueToUpdate["is_disabled"] = $is_disabled;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {
            
            $this->my_report_supervisor->update_supervisor_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_supervisor->is_success;
    
            if($is_success === true) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update supervisor success'
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
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update supervisor, check the data you sent'
                    )
                );
            }
        
    }
}
