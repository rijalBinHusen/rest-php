<?php
require_once(__DIR__ . '/../model/My_report_problem_model.php');

class My_report_problem
{   
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $my_report_problem;
    protected $result_from_model = null;

    function __construct()
    {
        $this->my_report_problem = new My_report_problem_model();
    }
    public function get_problems()
    {
        $req = Flight::request();
        $status = $req->query->status;
        $periode1 = $req->query->periode1;
        $periode2 = $req->query->periode2;
        $warehouse = $req->query->warehouse;
        $supervisor = $req->query->supervisor;
        $item = $req->query->item;
        // sennd data tomodel and accept the result
        if(!is_null($status)) {
            $this->result_from_model = $this->my_report_problem->get_problem_actives();
        }
        // return result of response function
        return $this->response();
    }
    public function add_problem()
    {
        $req = Flight::request();
        $id = $req->data->id;
        $warehouse_id = $req->data->warehouse_id;
        $supervisor_id = $req->data->supervisor_id;
        $head_spv_id = $req->data->head_spv_id;
        $item_kode = $req->data->item_kode;
        $tanggal_mulai = $req->data->tanggal_mulai;
        $shift_mulai = $req->data->shift_mulai;
        $pic = $req->data->pic;
        $dl = $req->data->dl;
        $masalah = $req->data->masalah;
        $sumber_masalah = $req->data->sumber_masalah;
        $solusi = $req->data->solusi;
        $solusi_panjang = $req->data->solusi_panjang;
        $dl_panjang = $req->data->dl_panjang;
        $pic_panjang = $req->data->pic_panjang;
        $tanggal_selesai = $req->data->tanggal_selesai;
        $shift_selesai = $req->data->shift_selesai;
        // append to database
        $this->result_from_model = $this->my_report_problem->append_problem(
                                        $id,
                                        $warehouse_id, 
                                        $supervisor_id,
                                        $head_spv_id, 
                                        $item_kode, 
                                        $tanggal_mulai, 
                                        $shift_mulai,
                                        $pic, 
                                        $dl, 
                                        $masalah, 
                                        $sumber_masalah, 
                                        $solusi, 
                                        $solusi_panjang, 
                                        $dl_panjang, 
                                        $pic_panjang, 
                                        $tanggal_selesai, 
                                        $shift_selesai
                                    );
        // return the result
        return $this->response();
        // return Flight::json(array(
        //     $id,
        //     $warehouse_id, 
        //     $supervisor_id,
        //     $head_spv_id, 
        //     $item_kode, 
        //     $tanggal_mulai, 
        //     $shift_mulai,
        //     $pic, 
        //     $dl, 
        //     $masalah, 
        //     $sumber_masalah, 
        //     $solusi, 
        //     $solusi_panjang, 
        //     $dl_panjang, 
        //     $pic_panjang, 
        //     $tanggal_selesai, 
        //     $shift_selesai
        // ));
    }
    public function get_problem_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $this->result_from_model = $this->my_report_problem->get_problem_by_id($id);
        // return the result
        return $this->response();
    }
    // public function deleteGuest($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     return $this->my_report_problem->deleteGuest($id);
    // }
    // public function update_supervisor_by_id($id)
    // {
    //     // catch the query string request
    //     $req = Flight::request();
    //     $supervisor_name = $req->data->supervisor_name;
    //     $supervisor_phone = $req->data->supervisor_phone;
    //     $supervisor_warehouse = $req->data->supervisor_warehouse;
    //     $supervisor_shift = $req->data->supervisor_shift;
    //     $is_disabled = $req->data->is_disabled;
    //     // initiate the column and values to update
    //     $keyValueToUpdate = null;
    //     // conditional supervisor_name
    //     if ($supervisor_name) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "supervisor_name='$supervisor_name'"
    //             : "$keyValueToUpdate, supervisor_name='$supervisor_name'";
    //     }

    //     // conditional is_disabled
    //     if (!is_null($is_disabled)) {
    //         $value = $is_disabled ? 1 : 0;
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "is_disabled='$value'"
    //             : "$keyValueToUpdate, is_disabled='$value'";
    //     }

    //     // conditional supervisor_shift
    //     if ($supervisor_shift) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "supervisor_shift='$supervisor_shift'"
    //             : "$keyValueToUpdate, supervisor_shift='$supervisor_shift'";
    //     }

    //     // conditional supervisor_warehouse
    //     if ($supervisor_warehouse) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "supervisor_warehouse='$supervisor_warehouse'"
    //             : "$keyValueToUpdate, supervisor_warehouse='$supervisor_warehouse'";
    //     }

    //     // conditional supervisor_phone
    //     if ($supervisor_phone) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "supervisor_phone='$supervisor_phone'"
    //             : "$keyValueToUpdate, supervisor_phone='$supervisor_phone'";
    //     }
    //     // send to myguest model
    //     $this->result_from_model = $this->my_report_problem->update_supervisor_by_id($keyValueToUpdate, $id);
    //     return $this->response();
    // }
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
