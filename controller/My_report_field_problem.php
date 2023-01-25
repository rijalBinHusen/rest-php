<?php
require_once(__DIR__ . '/../model/My_report_field_problem_model.php');

class My_report_field_problem
{   
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $my_field_report_problem;
    protected $result_from_model = null;
    protected $code = null;

    function __construct()
    {
        $this->my_field_report_problem = new My_report_field_problem_model();
    }
    public function get_field_problems()
    {
        // sennd data tomodel and accept the result
         $this->result_from_model = $this->my_field_report_problem->get_field_problems();
        
        // return result of response function
        return $this->response();
    }
    public function add_field_problem()
    {
        $req = Flight::request();
        $id = $req->data->id;
        $supervisor_id = $req->data->supervisor_id;
        $head_spv_id = $req->data->head_spv_id;
        $masalah = $req->data->masalah;
        $sumber_masalah = $req->data->sumber_masalah;
        $solusi = $req->data->solusi;
        $pic = $req->data->pic;
        $dl = $req->data->dl;
        // append to database
        $this->result_from_model = $this->my_field_report_problem->append_field_problem($id, $supervisor_id, $head_spv_id, $masalah, $sumber_masalah, $solusi, $pic, $dl);
        // return the result
        return $this->response();

    }
    public function get_field_problem_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $this->result_from_model = $this->my_field_report_problem->get_field_problem_by_id($id);
        // return the result
        return $this->response();
    }
    public function delete_field_problem($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        return $this->my_field_report_problem->delete_field_problem_by_id($id);
    }
    public function update_problem_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $periode = $req->data->periode;
        $supervisor_id = $req->data->supervisor_id;
        $head_spv_id = $req->data->head_spv_id;
        $masalah = $req->data->masalah;
        $sumber_masalah = $req->data->sumber_masalah;
        $solusi = $req->data->solusi;
        $pic = $req->data->pic;
        $dl = $req->data->dl;
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional periode
        if ($periode) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "periode='$periode'"
                : "$keyValueToUpdate, periode='$periode'";
        }

        // conditional supervisor_id
        if ($supervisor_id) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_id='$supervisor_id'"
                : "$keyValueToUpdate, supervisor_id='$supervisor_id'";
        }

        // conditional head_spv_id
        if ($head_spv_id) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "head_spv_id='$head_spv_id'"
                : "$keyValueToUpdate, head_spv_id='$head_spv_id'";
        }

        // conditional masalah
        if ($masalah) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "masalah='$masalah'"
                : "$keyValueToUpdate, masalah='$masalah'";
        }

        // conditional sumber_masalah
        if ($sumber_masalah) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "sumber_masalah='$sumber_masalah'"
                : "$keyValueToUpdate, sumber_masalah='$sumber_masalah'";
        }

        // conditional solusi
        if ($solusi) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "solusi='$solusi'"
                : "$keyValueToUpdate, solusi='$solusi'";
        }

        // conditional pic
        if ($pic) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "pic='$pic'"
                : "$keyValueToUpdate, pic='$pic'";
        }

        // conditional dl
        if ($dl) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "dl='$dl'"
                : "$keyValueToUpdate, dl='$dl'";
        }
        // send to myguest model
        $this->result_from_model = $this->my_field_report_problem->update_field_problem_by_id($keyValueToUpdate, $id);
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
