<?php
require_once(__DIR__ . '/../model/My_report_supervisor_model.php');

class My_report_warehouse
{   
    protected $status = null;
    protected $message = null;
    protected $result = "Failed to response request";
    protected $my_report_supervisor;

    function __construct()
    {
        $this->my_report_supervisor = new My_report_supervisor_model();
    }
    public function get_supervisors()
    {   
        $send_data_to_model = $this->my_report_supervisor->get_supervisors();
        if($send_data_to_model) {
            $this->status = 200;
            $this->result = $send_data_to_model;
        } else {
            $this->status = 400;
        }
        // return the result
        return Flight::json(array(
            'status' => 'success',
            $this->status == 200 
                ? 'data' => $this->result
                : 'message' => $this->message
        ), $this->status);
    }
    public function add_supervisor()
    {
        $req = Flight::request();
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        $supervisor_warehouse = $req->data->supervisor_warehouse;
        $supervisor_shift = $req->data->supervisor_shift;
        $is_disabled = $req->data->is_disabled;
        // append to database
        $send_data_to_model = $this->my_report_supervisor->append_supervisor($supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled);
        $result = $send_data_to_model
                    ? $send_data_to_model
                    : 'Failed to append data';
        // return the result
        if($send_data_to_model) {
            return Flight::json(array(
                'status' => 'success',
                'data' => $result
            ));
        } else {
            return Flight::json(array(
                'message' => $result,
            ), 400);
        }
    }
    public function get_warehouse_by_id($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        // append to database
        $send_data_to_model = $this->my_report_supervisor->get_supervisor_by_id($id);
        $result = $send_data_to_model
                    ? $send_data_to_model
                    : 'Failed to get data';
        // return the result
        if($send_data_to_model) {
            return Flight::json(array(
                'status' => 'success',
                'data' => $result
            ));
        } else {
            return Flight::json(array(
                'message' => $result,
            ), 400);
        }
    }
    // public function deleteGuest($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     return $this->my_report_supervisor->deleteGuest($id);
    // }
    public function update_warehouse_by_id($id) {
        // catch the query string request
        $req = Flight::request();
        $warehouse_name = $req->data->warehouse_name;
        $warehouse_group = $req->data->warehouse_group;
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional warehouse_name
        if($warehouse_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate) 
                ? "warehouse_name='$warehouse_name'" 
                : "$keyValueToUpdate warehouse_name='$warehouse_name'";
        } 

        // conditional warehouse$warehouse_group
        if($warehouse_group) {
            $keyValueToUpdate = is_null($keyValueToUpdate) 
                ? "warehouse_group='$warehouse_group'" 
                : "$keyValueToUpdate warehouse_group='$warehouse_group'";
        }
        // send to myguest model
        $this->my_report_supervisor->update_warehouse_by_id($keyValueToUpdate, $id);
    }
}
