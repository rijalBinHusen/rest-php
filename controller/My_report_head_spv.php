<?php
require_once(__DIR__ . '/../model/My_report_head_spv_model.php');

class My_report_head_spv
{
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $my_report_head_spv;
    protected $result_from_model = null;

    function __construct()
    {
        $this->my_report_head_spv = new My_report_head_spv_model();
    }
    public function get_heads_spv()
    {
        // sennd data tomodel and accept the result
        $this->result_from_model = $this->my_report_head_spv->get_heads();
        // return result of response function
        return $this->response();
    }
    public function add_head_spv()
    {
        $req = Flight::request();
        $head_spv_name = $req->data->head_spv_name;
        $head_spv_phone = $req->data->head_spv_phone;
        $head_spv_shift = $req->data->head_spv_shift;
        $is_disabled = $req->data->is_disabled;
        // append to database
        $this->result_from_model = $this->my_report_head_spv->append_head($head_spv_name, $head_spv_phone, $head_spv_shift, $is_disabled);
        // return the result
        return $this->response();
    }
    public function get_head_spv_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        // append to database
        $this->result_from_model = $this->my_report_head_spv->get_head_spv_by_id($id);
        // return the result
        return $this->response();
    }
    // public function deleteGuest($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     return $this->my_report_head_spv->deleteGuest($id);
    // }
    public function update_head_spv_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $head_name = $req->data->head_name;
        $head_phone = $req->data->head_phone;
        $head_warehouse = $req->data->head_warehouse;
        $head_shift = $req->data->head_shift;
        $is_disabled = $req->data->is_disabled;
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional head_name
        if ($head_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "head_name='$head_name'"
                : "$keyValueToUpdate, head_name='$head_name'";
        }

        // conditional is_disabled
        if (!is_null($is_disabled)) {
            $value = $is_disabled ? 1 : 0;
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "is_disabled='$value'"
                : "$keyValueToUpdate, is_disabled='$value'";
        }

        // conditional head_shift
        if ($head_shift) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "head_shift='$head_shift'"
                : "$keyValueToUpdate, head_shift='$head_shift'";
        }

        // conditional head_phone
        if ($head_phone) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "head_phone='$head_phone'"
                : "$keyValueToUpdate, head_phone='$head_phone'";
        }
        // send to myguest model
        $this->result_from_model = $this->my_report_head_spv->update_head_spv_by_id($keyValueToUpdate, $id);
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
            ,
            $this->code
        );
    }
}
