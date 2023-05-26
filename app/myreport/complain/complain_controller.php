<?php
require_once(__DIR__ . '/complain_model.php');

class My_report_complain
{
    protected $my_report_complain;
    function __construct()
    {
        $this->my_report_complain = new My_report_complain_model();
    }
    public function get_complains()
    { 
        $limit = Flight::request()->query->limit;

        $is_it_numeric = is_numeric($limit);

        if($is_it_numeric) {
            
            $result = $this->my_report_complain->get_complains($limit);
            
            $is_exists = count($result) > 0;

            if($this->my_report_complain->is_success === true && $is_exists) {
                Flight::json(
                    array(
                        "success" => true,
                        "data" => $result
                        )
                , 200);
            }

            else if ($this->my_report_complain->is_success !== true) {
                Flight::json( array(
                    "success" => false,
                    "message" => $result
                ), 500);
            }
            
            else {
                Flight::json( array(
                "success" => false,
                "message" => "Complain not found"
                ), 404);
            }
        }
            
        else {
            Flight::json( array(
            "success" => false,
            "message" => "The query request must be number"
            ), 400);
        }

    }
    public function add_complain()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $periode = $req->data->periode;
        $head_spv_id = $req->data->head_spv_id;
        $dl = $req->data->dl;
        $inserted = $req->data->inserted;
        $masalah = $req->data->masalah;
        $supervisor_id = $req->data->supervisor_id;
        $parent = $req->data->parent;
        $pic = $req->data->pic;
        $solusi = $req->data->solusi;
        $is_status_done = $req->data->is_status_done;
        $sumber_masalah = $req->data->sumber_masalah;
        $type = $req->data->type;
        $is_count = $req->data->is_count;

        $result = null;

        $is_request_body_oke = !is_null($periode) && !is_null($head_spv_id) && !is_null($dl) && !is_null($inserted) && !is_null($masalah) && !is_null($supervisor_id) && !is_null($parent) && !is_null($pic) && !is_null($solusi) && !is_null($is_status_done) && !is_null($sumber_masalah) && !is_null($type) && !is_null($is_count);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_complain->write_complain($id, $periode, $head_spv_id, $dl, $inserted, $masalah, $supervisor_id, $parent, $pic, $solusi, $is_count, $sumber_masalah, $type, $is_count);
            } else {
                // append warehouse
                $result = $this->my_report_complain->append_complain($periode, $head_spv_id, $dl, $inserted, $masalah, $supervisor_id, $parent, $pic, $solusi, $is_count, $sumber_masalah, $type, $is_count);
            }

            if($this->my_report_complain->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_complain->is_success
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
                'message' => 'Failed to add complain, check the data you sent'
            ), 400
        );
    }
    public function get_complain_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_complain->get_complain_by_id($id);

        $is_success = $this->my_report_complain->is_success;

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
                    'message' => 'Complain not found'
                )
            );
        }
    }

    public function remove_complain($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_complain->remove_complain($id);

        $is_success = $this->my_report_complain->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete complain success',
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
                    'message' => 'Complain not found'
                )
            );
        }
    }

    public function update_complain_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $periode = $req->data->periode;
        $head_spv_id = $req->data->head_spv_id;
        $dl = $req->data->dl;
        $inserted = $req->data->inserted;
        $masalah = $req->data->masalah;
        $supervisor_id = $req->data->supervisor_id;
        $parent = $req->data->parent;
        $pic = $req->data->pic;
        $solusi = $req->data->solusi;
        $is_status_done = $req->data->is_status_done;
        $sumber_masalah = $req->data->sumber_masalah;
        $type = $req->data->type;
        $is_count = $req->data->is_count;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional periode
        $valid_periode = !is_null($periode) && !empty($periode);
        if ($valid_periode) {
            $keyValueToUpdate["periode"] = $periode;
        }

        // conditional $head_spv_id
        $valid_head_spv_id = !is_null($head_spv_id) && !empty($head_spv_id);
        if ($valid_head_spv_id) {
            $keyValueToUpdate["head_spv_id"] = $head_spv_id;
        }

        // conditional $dl
        $valid_dl = !is_null($dl) && !empty($dl);
        if ($valid_dl) {
            $keyValueToUpdate["dl"] = $dl;
        }

        // conditional $inserted
        $valid_inserted = !is_null($inserted) && !empty($inserted);
        if ($valid_inserted) {
            $keyValueToUpdate["inserted"] = $inserted;
        }

        // conditional $masalah
        $valid_masalah = !is_null($masalah) && !empty($masalah);
        if ($valid_masalah) {
            $keyValueToUpdate["masalah"] = $masalah;
        }

        // conditional $dl
        $valid_supervisor_id = !is_null($supervisor_id) && !empty($supervisor_id);
        if ($valid_supervisor_id) {
            $keyValueToUpdate["supervisor_id"] = $supervisor_id;
        }

        // conditional $parent
        $valid_parent = !is_null($parent) && !empty($parent);
        if ($valid_parent) {
            $keyValueToUpdate["parent"] = $parent;
        }

        // conditional $pic
        $valid_pic = !is_null($pic) && !empty($pic);
        if ($valid_pic) {
            $keyValueToUpdate["pic"] = $pic;
        }

        // conditional $solusi
        $valid_solusi = !is_null($solusi) && !empty($solusi);
        if ($valid_solusi) {
            $keyValueToUpdate["solusi"] = $solusi;
        }

        // conditional $is_status_done
        $valid_is_status_done = !is_null($is_status_done) && !empty($is_status_done);
        if ($valid_is_status_done) {
            $keyValueToUpdate["is_status_done"] = $is_status_done;
        }

        // conditional $sumber_masalah
        $valid_sumber_masalah = !is_null($sumber_masalah) && !empty($sumber_masalah);
        if ($valid_sumber_masalah) {
            $keyValueToUpdate["sumber_masalah"] = $sumber_masalah;
        }

        // conditional $type
        $valid_type = !is_null($type) && !empty($type);
        if ($valid_type) {
            $keyValueToUpdate["type"] = $type;
        }

        // conditional $is_count
        $valid_is_count = !is_null($is_count) && !empty($is_count);
        if ($valid_is_count) {
            $keyValueToUpdate["is_count"] = $is_count;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_complain->update_complain_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_complain->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update complain success',
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
                        'message' => 'Complain not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update complain, check the data you sent'
                )
            );
        }

        
    }
}
