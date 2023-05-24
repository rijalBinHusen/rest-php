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
        $result = $this->my_report_complain->get_complains();
        
        if($this->my_report_complain->is_success) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }
        
        else {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
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

        if($periode && $head_spv_id && $dl) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_complain->write_complain($id, $periode, $head_spv_id, $dl);
            } else {
                // append warehouse
                $result = $this->my_report_complain->append_complain($periode, $head_spv_id, $dl);
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
                'message' => 'Failed to add base item, check the data you sent'
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
                    'message' => 'Base item not found'
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
                    'message' => 'Delete base item success',
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
                    'message' => 'Base item not found'
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

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_complain->update_complain_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_complain->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update base item success',
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
                        'message' => 'Base item not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update base item, check the data you sent'
                )
            );
        }

        
    }
}
