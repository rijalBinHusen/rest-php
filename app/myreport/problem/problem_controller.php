<?php
require_once(__DIR__ . '/problem_model.php');

class My_report_problem
{
    protected $my_report_problem;
    function __construct()
    {
        $this->my_report_problem = new My_report_problem_model();
    }

    public function add_problem()
    {
        // request
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
        $is_finished = $req->data->is_finished;

        $result = null;

        $valid_request_body =   !is_null($warehouse_id) 
                                && !is_null($supervisor_id) 
                                && !is_null($head_spv_id) 
                                && !is_null($item_kode) 
                                && !is_null($tanggal_mulai) 
                                && !is_null($shift_mulai) 
                                && !is_null($pic)
                                && !is_null($dl)
                                && !is_null($masalah)
                                && !is_null($sumber_masalah)
                                && !is_null($solusi)
                                && !is_null($solusi_panjang)
                                && !is_null($dl_panjang)
                                && !is_null($pic_panjang)
                                && !is_null($tanggal_selesai)
                                && !is_null($is_finished);
        
                                if($valid_request_body) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_problem->write_problem(
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
                    $shift_selesai,
                    $is_finished
                    );
            } else {
                // append warehouse
                $result = $this->my_report_problem->append_problem(
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
                                                            $shift_selesai,
                                                            $is_finished
                                                        );
            }

            if($this->my_report_problem->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_problem->is_success
                    ), 500
                );
                return;
            }
            
            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ), 201
            );
            return;
        }

        Flight::json(
            array(
                'success' => false,
                'message' => 'Failed to add base clock, check the data you sent'
            ), 400
        );
    }

    public function get_problem_by_periode()
    { 
        $request = Flight::request();
        $periode1 = $request->query->periode1;
        $periode2 = $request->query->periode2;

        $not_valid_query_string = is_null($periode1) 
                                    || empty($periode1) 
                                    || !is_numeric($periode1) 
                                    || is_null($periode2) 
                                    || empty($periode2) 
                                    || !is_numeric($periode2);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_problem->get_problem_by_periode($periode1, $periode2);
        
        $is_exists = count($result) > 0;

        if($this->my_report_problem->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_problem->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Problem record not found"
                )
            , 404);
        }

    }

    public function get_problem_by_status()
    { 
        $request = Flight::request();
        $status = $request->query->status;

        $not_valid_query_string = is_null($status) 
                                    || !is_numeric($status) 
                                    || $status < -1 
                                    || $status > 2;

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "'Please check query parameter'" . !is_numeric($status)
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_problem->get_problem_by_status($status);
        
        $is_exists = count($result) > 0;

        if($this->my_report_problem->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_problem->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Problem record not found"
                )
            , 404);
        }

    }

    public function get_problem_by_supervisor()
    { 
        $request = Flight::request();
        $supervisor_id = $request->query->supervisor_id;

        $not_valid_query_string = is_null($supervisor_id) 
                                    || empty($supervisor_id) 
                                    || !is_string($supervisor_id);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_problem->get_problem_by_supervisor($supervisor_id);
        
        $is_exists = count($result) > 0;

        if($this->my_report_problem->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_problem->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Problem record not found"
                )
            , 404);
        }

    }

    public function get_problem_by_warehouse_and_item()
    { 
        $request = Flight::request();
        $warehouse_id = $request->query->warehouse_id;
        $item_kode = $request->query->item_kode;

        $not_valid_query_string = is_null($warehouse_id) 
                                    || empty($warehouse_id) 
                                    || !is_string($warehouse_id)
                                    || is_null($item_kode) 
                                    || empty($item_kode) 
                                    || !is_string($item_kode);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_problem->get_problem_by_warehouse_and_item($warehouse_id, $item_kode);
        
        $is_exists = count($result) > 0;

        if($this->my_report_problem->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_problem->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Problem record not found"
                )
            , 404);
        }

    }
    
    public function get_problem_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_problem->get_problem_by_id($id);

        $is_success = $this->my_report_problem->is_success;

        $is_found = count($result) > 0;

        if($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Problem record not found'
                )
            );
        }
    }
    
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
        $is_finished = $req->data->is_finished;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $warehouse_id
        $valid_warehouse_id = !is_null($warehouse_id) && !empty($warehouse_id);
        if ($valid_warehouse_id) {
            $keyValueToUpdate["warehouse_id"] = $warehouse_id;
        }

        // conditional supervisor_id
        $valid_supervisor_id = !is_null($supervisor_id) && !empty($supervisor_id);
        if ($valid_supervisor_id) {
            $keyValueToUpdate["supervisor_id"] = $supervisor_id;
        }

        // conditional $head_spv_id
        $valid_head_spv_id = !is_null($head_spv_id) && !empty($head_spv_id);
        if ($valid_head_spv_id) {
            $keyValueToUpdate["head_spv_id"] = $head_spv_id;
        }

        // conditional $item_kode
        $valid_item_kode = !is_null($item_kode) && !empty($item_kode);
        if ($valid_item_kode) {
            $keyValueToUpdate["item_kode"] = $item_kode;
        }

        // conditional $tanggal_mulai
        $valid_tanggal_mulai = !is_null($tanggal_mulai) && !empty($tanggal_mulai);
        if ($valid_tanggal_mulai) {
            $keyValueToUpdate["tanggal_mulai"] = $tanggal_mulai;
        }

        // conditional $shift_mulai
        $shift_mulai = !is_null($shift_mulai) && !empty($shift_mulai);
        if ($shift_mulai) {
            $keyValueToUpdate["shift_mulai"] = $shift_mulai;
        }

        // conditional $pic
        $valid_pic = !is_null($pic) && !empty($pic);
        if ($valid_pic) {
            $keyValueToUpdate["pic"] = $pic;
        }

        // conditional $dl
        $valid_dl = !is_null($dl) && !empty($dl);
        if ($valid_dl) {
            $keyValueToUpdate["dl"] = $dl;
        }

        // conditional $masalah
        $valid_masalah = !is_null($masalah) && !empty($masalah);
        if ($valid_masalah) {
            $keyValueToUpdate["masalah"] = $masalah;
        }

        // conditional $sumber_masalah
        $valid_sumber_masalah = !is_null($sumber_masalah) && !empty($sumber_masalah);
        if ($valid_sumber_masalah) {
            $keyValueToUpdate["sumber_masalah"] = $sumber_masalah;
        }

        // conditional $solusi
        $valid_solusi = !is_null($solusi) && !empty($solusi);
        if ($valid_solusi) {
            $keyValueToUpdate["solusi"] = $solusi;
        }

        // conditional $solusi_panjang
        $valid_solusi_panjang = !is_null($solusi_panjang) && !empty($solusi_panjang);
        if ($valid_solusi_panjang) {
            $keyValueToUpdate["solusi_panjang"] = $solusi_panjang;
        }

        // conditional $dl_panjang
        $valid_dl_panjang = !is_null($dl_panjang) && !empty($dl_panjang);
        if ($valid_dl_panjang) {
            $keyValueToUpdate["dl_panjang"] = $dl_panjang;
        }

        // conditional $pic_panjang
        $valid_pic_panjang = !is_null($pic_panjang) && !empty($pic_panjang);
        if ($valid_pic_panjang) {
            $keyValueToUpdate["pic_panjang"] = $pic_panjang;
        }

        // conditional $tanggal_selesai
        $valid_tanggal_selesai = !is_null($tanggal_selesai) && !empty($tanggal_selesai);
        if ($valid_tanggal_selesai) {
            $keyValueToUpdate["tanggal_selesai"] = $tanggal_selesai;
        }

        // conditional $shift_selesai
        $valid_shift_selesai = !is_null($shift_selesai) && !empty($shift_selesai);
        if ($valid_shift_selesai) {
            $keyValueToUpdate["shift_selesai"] = $shift_selesai;
        }

        // conditional $is_finished
        $valid_is_finished = !is_null($is_finished) && !empty($is_finished);
        if ($valid_is_finished) {
            $keyValueToUpdate["is_finished"] = $is_finished;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_problem->update_problem_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_problem->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update problem success',
                    )
                );
            }
    
            else if($is_success !== true) {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ), 500
                );
                return;
            }
    
            else {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'Problem record not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update problem, check the data you sent'
                )
            );
        }

        
    }

}
