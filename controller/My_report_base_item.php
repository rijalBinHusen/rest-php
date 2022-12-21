<?php
require_once(__DIR__ . '/../model/My_report_base_item_model.php');

class My_report_base_item
{   
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $base_item_model;
    protected $result_from_model = null;

    function __construct()
    {
        $this->base_item_model = new My_report_base_item_model();
    }
    public function get_items()
    {
        // sennd data tomodel and accept the result
        $this->result_from_model = $this->base_item_model->get_items();
        // return result of response function
        return $this->response();
    }
    public function add_item()
    {
        $req = Flight::request();
        $id = $req->data->id;
        $kode = $req->data->kode;
        $name = $req->data->name;
        $last_used = $req->data->last_used;
        
        if($id) {
            // write to database
            $this->result_from_model = $this->base_item_model->write_item($id, $kode, $name, $last_used);
        } else {
            // append to database
            $this->result_from_model = $this->base_item_model->append_item($kode, $name, $last_used);
        }
        // return the result
        return $this->response();
    }
    public function get_item_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        // append to database
        $this->result_from_model = $this->base_item_model->get_item_by_id($id);
        // return the result
        return $this->response();
    }
    public function delete_item($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $this->result_from_model = $this->base_item_model->delete_item($id);
        return $this->response();
    }
    public function update_item_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $kode = $req->data->kode;
        $name = $req->data->name;
        $last_used = $req->data->last_used;
        
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional kode
        if ($kode) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "item_kode='$kode'"
                : "$keyValueToUpdate, item_kode='$kode'";
        }

        // conditional last_used
        if ($last_used) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "last_used='$last_used'"
                : "$keyValueToUpdate, last_used='$last_used'";
        }

        // conditional name
        if ($name) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "item_name='$name'"
                : "$keyValueToUpdate, item_name='$name'";
        }
        // send to myguest model
        $this->result_from_model = $this->base_item_model->update_item_by_id($keyValueToUpdate, $id);
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
