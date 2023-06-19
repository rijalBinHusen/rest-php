<?php
require_once(__DIR__ . '/head_spv_model.php');

class My_report_head_spv
{
    protected $my_report_head_spv;
    function __construct()
    {
        $this->my_report_head_spv = new My_report_head_spv_model();
    }
    public function get_heads_spv()
    { 
        $result = $this->my_report_head_spv->get_heads_spv();
        
        if($this->my_report_head_spv->is_success) {
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
    public function add_head_spv()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $head_name = $req->data->head_name;
        $head_phone = $req->data->head_phone;
        $head_shift = $req->data->head_shift;
        $is_disabled = $req->data->is_disabled;

        $result = null;

        $is_request_body_oke = !is_null($head_name) && !is_null($head_phone) && !is_null($head_shift) && !is_null($is_disabled);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_head_spv->write_head_spv($id, $head_name, $head_phone, $head_shift, $is_disabled);
            } else {
                // append warehouse
                $result = $this->my_report_head_spv->append_head_spv($head_name, $head_phone, $head_shift, $is_disabled);
            }

            if($this->my_report_head_spv->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_head_spv->is_success
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
                'message' => 'Failed to add head supervisor, check the data you sent'
            ), 400
        );
    }
    public function get_head_spv_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_head_spv->get_head_spv_by_id($id);

        $is_success = $this->my_report_head_spv->is_success;

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
                    'message' => 'Head supervisor not found'
                )
            );
        }
    }

    // // public function deleteGuest($id) {
    // //     // myguest/8
    // //     // the 8 will automatically becoming parameter $id
    // //     return $this->my_report_head_spv->deleteGuest($id);
    // // }

    public function update_head_spv_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $head_name = $req->data->head_name;
        $head_phone = $req->data->head_phone;
        $head_shift = $req->data->head_shift;
        $is_disabled = $req->data->is_disabled;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional head_name
        $valid_head_name = !is_null($head_name) && !empty($head_name);
        if ($valid_head_name) {
            $keyValueToUpdate["head_name"] = $head_name;
        }

        // conditional $head_phone
        $valid_head_phone = !is_null($head_phone) && !empty($head_phone);
        if ($valid_head_phone) {
            $keyValueToUpdate["head_phone"] = $head_phone;
        }

        // conditional $head_shift
        $valid_head_shift = !is_null($head_shift) && !empty($head_shift);
        if ($valid_head_shift) {
            $keyValueToUpdate["head_shift"] = $head_shift;
        }

        // conditional $is_disabled
        $valid_is_disabled = !is_null($is_disabled) && !empty($is_disabled);
        if ($valid_is_disabled) {
            $keyValueToUpdate["is_disabled"] = $is_disabled;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {
            $this->my_report_head_spv->update_head_spv_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_head_spv->is_success;
    
            if($is_success === true) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update head supervisor success'
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
                        'message' => 'Supervisor not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update head supervisor, check the data you sent'
                )
            );
        }

        
    }
}
