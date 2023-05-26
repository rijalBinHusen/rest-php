<?php
require_once(__DIR__ . '/field_problem_model.php');

class My_report_field_problem
{
    protected $my_report_field_problem;
    function __construct()
    {
        $this->my_report_field_problem = new My_report_field_problem_model();
    }
    public function get_field_problems()
    { 
        $limit = Flight::request()->query->limit;
        
        $is_it_numeric = is_numeric($limit);

        if($is_it_numeric) {
            $result = $this->my_report_field_problem->get_field_problems();
            
            $is_exists = count($result) > 0;

            if($this->my_report_field_problem->is_success === true && $is_exists) {
                Flight::json(
                    array(
                        "success" => true,
                        "data" => $result
                        )
                , 200);
            }

            else if ($this->my_report_field_problem->is_success !== true) {
                Flight::json( array(
                    "success" => false,
                    "message" => $result
                ), 500);
            }
            
            else {
                Flight::json( array(
                "success" => false,
                "message" => "Field problem not found"
                ), 404);
            }
        }
        
        else {
            Flight::json(array(
                "success" => false,
                "message" => "The query parameter must be number"
                )
            , 400);
        }

    }
    public function add_field_problem()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $periode = $req->data->periode;
        $supervisor_id = $req->data->supervisor_id;
        $head_spv_id = $req->data->head_spv_id;
        $masalah = $req->data->masalah;
        $sumber_masalah = $req->data->sumber_masalah;
        $solusi = $req->data->solusi;
        $pic = $req->data->pic;
        $dl = $req->data->dl;

        $result = null;

        $is_request_body_oke = !is_null($supervisor_id) && !is_null($head_spv_id) && !is_null($masalah) && !is_null($sumber_masalah) && !is_null($solusi) && !is_null($pic) && !is_null($dl);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_field_problem->write_field_problem($id, $periode, $supervisor_id, $head_spv_id, $masalah, $sumber_masalah, $solusi, $pic, $dl);
            } else {
                // append warehouse
                $result = $this->my_report_field_problem->append_field_problem($periode, $supervisor_id, $head_spv_id, $masalah, $sumber_masalah, $solusi, $pic, $dl);
            }

            if($this->my_report_field_problem->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_field_problem->is_success
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
                'message' => 'Failed to add field problem, check the data you sent'
            ), 400
        );
    }
    public function get_field_problem_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_field_problem->get_field_problem_by_id($id);

        $is_success = $this->my_report_field_problem->is_success;

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
                    'message' => 'Field problem not found'
                )
            );
        }
    }

    public function remove_field_problem($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_field_problem->remove_field_problem($id);

        $is_success = $this->my_report_field_problem->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete Field problem success',
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
                    'message' => 'Field problem not found'
                )
            );
        }
    }

    public function update_field_problem_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $supervisor_id = $req->data->supervisor_id;
        $periode = $req->data->periode;
        $head_spv_id = $req->data->head_spv_id;
        $masalah = $req->data->masalah;
        $sumber_masalah = $req->data->sumber_masalah;
        $solusi = $req->data->solusi;
        $pic = $req->data->pic;
        $dl = $req->data->dl;

        // initiate the column and values to update
        $keyValueToUpdate = array();
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
        $solusi = !is_null($solusi) && !empty($solusi);
        if ($solusi) {
            $keyValueToUpdate["solusi"] = $solusi;
        }

        // conditional $masalah
        $valid_pic = !is_null($pic) && !empty($pic);
        if ($valid_pic) {
            $keyValueToUpdate["pic"] = $pic;
        }

        // conditional $periode
        $valid_periode = !is_null($periode) && !empty($periode);
        if ($valid_periode) {
            $keyValueToUpdate["periode"] = $periode;
        }

        // conditional $dl
        $valid_dl = !is_null($dl) && !empty($dl);
        if ($valid_dl) {
            $keyValueToUpdate["dl"] = $dl;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_field_problem->update_field_problem_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_field_problem->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update field problem success',
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
                        'message' => 'Field problem not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update field problem, check the data you sent'
                )
            );
        }

        
    }
}
