<?php
require_once(__DIR__ . '/case_model.php');

class My_report_case
{
    protected $my_report_case;
    function __construct()
    {
        $this->my_report_case = new My_report_case_model();
    }
    public function get_cases()
    { 
        $limit = Flight::request()->query->limit;
        
        $is_it_numeric = is_numeric($limit);

        if($is_it_numeric) {

            $result = $this->my_report_case->get_cases($limit);
            
            $is_exists = count($result) > 0;

            if($this->my_report_case->is_success === true && $is_exists) {
                Flight::json(
                    array(
                        "success" => true,
                        "data" => $result
                        )
                , 200);
            }

            else if ($this->my_report_case->is_success !== true) {
                Flight::json( array(
                    "success" => false,
                    "message" => $result
                ), 500);
            }
            
            else {
                Flight::json( array(
                "success" => false,
                "message" => "Case import not found"
                ), 404);
            }
        } else {
            Flight::json(array(
                "success" => false,
                "message" => "The query request must be number"
                )
            , 400);
        }

    }
    public function add_case()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $periode = $req->data->periode;
        $head_spv_id = $req->data->head_spv_id;
        $dl = $req->data->dl;
        $masalah = $req->data->masalah;
        $supervisor_id = $req->data->supervisor_id;
        $parent = $req->data->parent;
        $pic = $req->data->pic;
        $solusi = $req->data->solusi;
        $status = $req->data->status;
        $sumber_masalah = $req->data->sumber_masalah;

        $result = null;

        $is_request_body_oke = !is_null($periode) && !is_null($head_spv_id) && !is_null($dl) && !is_null($masalah) && !is_null($supervisor_id) && !is_null($parent) && !is_null($pic) && !is_null($solusi) && !is_null($status) && !is_null($sumber_masalah);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_case->write_case($id, $periode, $head_spv_id, $dl, $masalah, $supervisor_id, $parent, $pic, $solusi, $status, $sumber_masalah);
            } else {
                // append warehouse
                $result = $this->my_report_case->append_case($periode, $head_spv_id, $dl, $masalah, $supervisor_id, $parent, $pic, $solusi, $status, $sumber_masalah);
            }

            if($this->my_report_case->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_case->is_success
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
                'message' => 'Failed to add case, check the data you sent'
            ), 400
        );
    }
    public function get_case_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_case->get_case_by_id($id);

        $is_success = $this->my_report_case->is_success;

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
                    'message' => 'Case not found'
                ), 404
            );
        }
    }

    public function remove_case($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_case->remove_case($id);

        $is_success = $this->my_report_case->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete case success',
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
                    'message' => 'Case not found'
                ), 404
            );
        }
    }

    public function update_case_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $head_spv_id = $req->data->head_spv_id;
        $periode = $req->data->periode;
        $dl = $req->data->dl;
        $masalah = $req->data->masalah;
        $supervisor_id = $req->data->supervisor_id;
        $parent = $req->data->parent;
        $pic = $req->data->pic;
        $solusi = $req->data->solusi;
        $status = $req->data->status;
        $sumber_masalah = $req->data->sumber_masalah;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional head_spv_id
        $valid_head_spv_id = !is_null($head_spv_id) && !empty($head_spv_id);
        if ($valid_head_spv_id) {
            $keyValueToUpdate["head_spv_id"] = $head_spv_id;
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

        // conditional $supervisor_id
        $valid_supervisor_id = !is_null($supervisor_id) && !empty($supervisor_id);
        if ($valid_supervisor_id) {
            $keyValueToUpdate["supervisor_id"] = $supervisor_id;
        }

        // conditional $parent
        $valid_parent = !is_null($parent) && !empty($parent);
        if ($valid_parent) {
            $keyValueToUpdate["parent"] = $parent;
        }

        // conditional $masalah
        $valid_pic = !is_null($pic) && !empty($pic);
        if ($valid_pic) {
            $keyValueToUpdate["pic"] = $pic;
        }

        // conditional $solusi
        $valid_solusi = !is_null($solusi) && !empty($solusi);
        if ($valid_solusi) {
            $keyValueToUpdate["solusi"] = $solusi;
        }

        // conditional $status
        $valid_status = !is_null($status) && !empty($status);
        if ($valid_status) {
            $keyValueToUpdate["status"] = $status;
        }

        // conditional $sumber_masalah
        $valid_sumber_masalah = !is_null($sumber_masalah) && !empty($sumber_masalah);
        if ($valid_sumber_masalah) {
            $keyValueToUpdate["sumber_masalah"] = $sumber_masalah;
        }

        // conditional $periode
        $valid_periode = !is_null($periode) && !empty($periode);
        if ($valid_periode) {
            $keyValueToUpdate["periode"] = $periode;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_case->update_case_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_case->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update case success',
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
                        'message' => 'Case not found'
                    ), 404
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update case, check the data you sent'
                )
            );
        }

        
    }
}
