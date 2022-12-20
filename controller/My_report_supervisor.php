<?php
require_once(__DIR__ . '/../model/My_report_supervisor_model.php');

class My_report_warehouse
{   
    // variabel for http code status
    protected $code = null;
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
            $this->code = 200;
            $this->result = $send_data_to_model;
        } else {
            $this->code = 400;
        }
        $respose = $this->response();
        // return the result
        return $respose;
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
        if($send_data_to_model) {
            $this->code = 200;
            $this->result = $send_data_to_model;
        } else {
            $this->code = 400;
        }
        $respose = $this->response();
        // return the result
        return $respose;
    }
    public function get_supervisor_by_id($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        // append to database
        $send_data_to_model = $this->my_report_supervisor->get_supervisor_by_id($id);
        if($send_data_to_model) {
            $this->code = 200;
            $this->result = $send_data_to_model;
        } else {
            $this->code = 400;
        }
        $respose = $this->response();
        // return the result
        return $respose;
    }
    // public function deleteGuest($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     return $this->my_report_supervisor->deleteGuest($id);
    // }
    public function update_superivsor_by_id($id) {
        // catch the query string request
        $req = Flight::request();
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        $supervisor_warehouse = $req->data->supervisor_warehouse;
        $supervisor_shift = $req->data->supervisor_shift;
        $is_disabled = $req->data->is_disabled;
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional warehouse_name
        if($supervisor_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate) 
                ? "supervisor_name='$supervisor_name'" 
                : "$keyValueToUpdate supervisor_name='$supervisor_name'";
        } 

        // conditional warehouse$warehouse_group
        if($supervisor_phone) {
            $keyValueToUpdate = is_null($keyValueToUpdate) 
                ? "supervisor_p$supervisor_phone='$supervisor_phone'" 
                : "$keyValueToUpdate supervisor_p$supervisor_phone='$supervisor_phone'";
        }
        // send to myguest model
        $this->my_report_supervisor->update_warehouse_by_id($keyValueToUpdate, $id);
    }
    public function response() {
        return Flight::json(array(
            'status' => $this->status == 200 ? 'success' : 'failed',
            'data' => $this->result
        ), $this->status);
    }
}
