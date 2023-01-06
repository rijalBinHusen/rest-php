<?php
require_once(__DIR__ . '/../model/My_report_problem_model.php');

class My_report_problem
{   
    // variabel for http code status
    protected $result = "Failed to response request";
    protected $my_report_problem;
    protected $result_from_model = null;
    protected $code = null;

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
        } else if($periode1 and $periode2) {
            $this->result_from_model = $this->my_report_problem->get_problem_between_periode($periode1, $periode2);
        }else if($item) {
            $this->result_from_model = $this->my_report_problem->get_problem_by_item_kode($item);
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
    public function update_problem_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
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
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional warehouse_id
        if ($warehouse_id) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "warehouse_id='$warehouse_id'"
                : "$keyValueToUpdate, warehouse_id='$warehouse_id'";
        }

        // conditional tanggal_mulai
        if ($tanggal_mulai) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "tanggal_mulai='$tanggal_mulai'"
                : "$keyValueToUpdate, tanggal_mulai='$tanggal_mulai'";
        }

        // conditional shift_mulai
        if ($shift_mulai){
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "shift_mulai='$shift_mulai'"
                : "$keyValueToUpdate, shift_mulai='$shift_mulai'";
        }

        // conditional pic
        if ($pic) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "pic='$pic'"
                : "$keyValueToUpdate, pic='$pic'";
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

        // conditional dl_panjang
        if ($dl_panjang) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "dl_panjang='$dl_panjang'"
                : "$keyValueToUpdate, dl_panjang='$dl_panjang'";
        }

        // conditional pic_panjang
        if ($pic_panjang) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "pic_panjang='$pic_panjang'"
                : "$keyValueToUpdate, pic_panjang='$pic_panjang'";
        }

        // conditional tanggal_selesai
        if ($tanggal_selesai) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "tanggal_selesai='$tanggal_selesai'"
                : "$keyValueToUpdate, tanggal_selesai='$tanggal_selesai'";
        }

        // conditional shift_selesai
        if ($shift_selesai) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "shift_selesai='$shift_selesai'"
                : "$keyValueToUpdate, shift_selesai='$shift_selesai'";
        }

        // conditional dl
        if ($dl) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "dl='$dl'"
                : "$keyValueToUpdate, dl='$dl'";
        }

        // conditional solusi_panjang
        if ($solusi_panjang) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "solusi_panjang='$solusi_panjang'"
                : "$keyValueToUpdate, solusi_panjang='$solusi_panjang'";
        }

        // conditional item_kode
        if ($item_kode) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "item_kode='$item_kode'"
                : "$keyValueToUpdate, item_kode='$item_kode'";
        }

        // conditional head_spv_id
        if ($head_spv_id) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "head_spv_id='$head_spv_id'"
                : "$keyValueToUpdate, head_spv_id='$head_spv_id'";
        }

        // conditional supervisor_id
        if ($supervisor_id) {
            $keyValueToUpdate = is_null($keyValueToUpdate)
                ? "supervisor_id='$supervisor_id'"
                : "$keyValueToUpdate, supervisor_id='$supervisor_id'";
        }
        // send to myguest model
        $this->result_from_model = $this->my_report_problem->update_problem_by_id($keyValueToUpdate, $id);
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
