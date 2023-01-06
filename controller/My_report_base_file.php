<?php
require_once(__DIR__ . '/../model/My_report_base_file_model.php');

class My_report_base_file
{   
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $base_file_model;
    protected $result_from_model = null;
    protected $code = null;

    function __construct()
    {
        $this->base_file_model = new My_report_base_file_model();
    }
    // public function get_items()
    // {
    //     // sennd data tomodel and accept the result
    //     $this->result_from_model = $this->base_file_model->get_items();
    //     // return result of response function
    //     return $this->response();
    // }
    public function add_base_file()
    {
        $req = Flight::request();
        $id = $req->data->id;
        $periode = $req->data->periode;
        $warehouse_id = $req->data->warehouse_id;
        // append to database
        $this->result_from_model = $this->base_file_model->append_base_file($id, $periode, $warehouse_id);
        // return the result
        return $this->response();
    }
    public function get_base_file_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        // append to database
        $this->result_from_model = $this->base_file_model->get_base_file_by_id($id);
        // return the result
        return $this->response();
    }
    public function delete_base_file($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $this->result_from_model = $this->base_file_model->delete_base_file($id);
        return $this->response();
    }
    public function update_base_file_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $periode = $req->data->periode;
        $warehouse_id = $req->data->warehouse_id;
        $file_name = $req->data->file_name;
        $clock_sheet = $req->data->clock_sheet;
        $stock_sheet = $req->data->stock_sheet;
        $is_imported = $req->data->is_imported;
        
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional periode
        if ($periode) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "periode='$periode'"
                : "$keyValueToUpdate, periode='$periode'";
        }

        // conditional warehouse_id
        if ($warehouse_id) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "warehouse_id='$warehouse_id'"
                : "$keyValueToUpdate, warehouse_id='$warehouse_id'";
        }

        // conditional file_name
        if ($file_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "file_name='$file_name'"
                : "$keyValueToUpdate, file_name='$file_name'";
        }

        // conditional clock_sheet
        if ($clock_sheet) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "clock_sheet='$clock_sheet'"
                : "$keyValueToUpdate, clock_sheet='$clock_sheet'";
        }

        // conditional clock_sheet
        if ($stock_sheet) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "stock_sheet='$stock_sheet'"
                : "$keyValueToUpdate, stock_sheet='$stock_sheet'";
        }

        // conditional is_imported
        if ($is_imported) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "is_imported='$is_imported'"
                : "$keyValueToUpdate, is_imported='$is_imported'";
        }
        // send to myguest model
        $this->result_from_model = $this->base_file_model->update_base_file_by_id($keyValueToUpdate, $id);
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
    public function get_base_files_between_two_periode()
    {
        // catch the query string request
        $req = Flight::request();
        // get the url query periode 1
        $periode1 = $req->query->periode1;
        $periode2 = $req->query->periode2;
        
        // send to myguest model
        $this->result_from_model = $this->base_file_model->get_base_files_between_two_periode($periode1, $periode2);
        return $this->response();
    }
}
