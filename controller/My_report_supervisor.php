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
        // sennd data tomodel and accept the result
        $this->result_from_model = $this->my_report_supervisor->get_supervisors();
        // return result of response function
        return $this->response();
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
        $this->result_from_model = $this->my_report_supervisor->append_supervisor($supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled);
        // return the result
        return $this->response();
    }
    public function get_superviosr_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        // append to database
        $this->result_from_model = $this->my_report_supervisor->get_supervisor_by_id($id);
        // return the result
        return $this->response();
    }
    // public function deleteGuest($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     return $this->my_report_supervisor->deleteGuest($id);
    // }
    public function update_warehouse_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional supervisor_name
        if ($supervisor_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_name='$supervisor_name'"
                : "$keyValueToUpdate supervisor_name='$supervisor_name'";
        }

        // conditional warehouse$supervisor_phone
        if ($supervisor_phone) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_phone='$supervisor_phone'"
                : "$keyValueToUpdate supervisor_phone='$supervisor_phone'";
        }
        // send to myguest model
        $this->my_report_supervisor->update_supervisor_by_id($keyValueToUpdate, $id);
    }
    protected function response()
    {
        if ($this->result_from_model) {
            // set the http status code 200
            $this->code = 200;
            // set the result data that would be return to user
            $this->result = $this->result_from_model;
        } else {
            // set the http status code 200
            $this->code = 400;
        }
        // return the result
        return Flight::json(array(
            // the result
            $this->result_from_model ? $this->result_from_model : $this->result
            // and the code
        ), $this->code);
    }
}
