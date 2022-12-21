<?php
require_once(__DIR__ . '/../model/My_report_supervisor_model.php');

class My_report_supervisor
{   
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $my_report_supervisor;
    protected $result_from_model = null;

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
        $id = $req->data->id;
        $supervisor_name = $req->data->supervisor_name;
        $supervisor_phone = $req->data->supervisor_phone;
        $supervisor_warehouse = $req->data->supervisor_warehouse;
        $supervisor_shift = $req->data->supervisor_shift;
        $is_disabled = $req->data->is_disabled;
        if($id) {
            // write warehouse
            $this->result_from_model = $this->my_report_supervisor->write_supervisor($id, $supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled);
        } else {
            // append to database
            $this->result_from_model = $this->my_report_supervisor->append_supervisor($supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled);
        }
        // return the result
        return $this->response();
    }
    public function get_supervisor_by_id($id)
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
        $keyValueToUpdate = null;
        // conditional supervisor_name
        if ($supervisor_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_name='$supervisor_name'"
                : "$keyValueToUpdate, supervisor_name='$supervisor_name'";
        }

        // conditional is_disabled
        if (!is_null($is_disabled)) {
            $value = $is_disabled ? 1 : 0;
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "is_disabled='$value'"
                : "$keyValueToUpdate, is_disabled='$value'";
        }

        // conditional supervisor_shift
        if ($supervisor_shift) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_shift='$supervisor_shift'"
                : "$keyValueToUpdate, supervisor_shift='$supervisor_shift'";
        }

        // conditional supervisor_warehouse
        if ($supervisor_warehouse) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_warehouse='$supervisor_warehouse'"
                : "$keyValueToUpdate, supervisor_warehouse='$supervisor_warehouse'";
        }

        // conditional supervisor_phone
        if ($supervisor_phone) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_phone='$supervisor_phone'"
                : "$keyValueToUpdate, supervisor_phone='$supervisor_phone'";
        }
        // send to myguest model
        $this->result_from_model = $this->my_report_supervisor->update_supervisor_by_id($keyValueToUpdate, $id);
        return $this->response();
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
        return Flight::json(
            // the result
            $this->result
            // and the code
        , $this->code);
    }
}
