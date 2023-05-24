<?php
require_once(__DIR__ . '/base_item_model.php');

class My_report_base_item
{
    protected $my_report_base_item;
    function __construct()
    {
        $this->my_report_base_item = new My_report_base_item_model();
    }
    public function get_base_items()
    { 
        $result = $this->my_report_base_item->get_base_items();
        
        if($this->my_report_base_item->is_success) {
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
    public function add_base_item()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $item_kode = $req->data->item_kode;
        $item_name = $req->data->item_name;
        $last_used = $req->data->last_used;

        $result = null;

        if($item_kode && $item_name && $last_used) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_base_item->write_base_item($id, $item_kode, $item_name, $last_used);
            } else {
                // append warehouse
                $result = $this->my_report_base_item->append_base_item($item_kode, $item_name, $last_used);
            }

            if($this->my_report_base_item->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_base_item->is_success
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
    public function get_base_item_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_item->get_base_item_by_id($id);

        $is_success = $this->my_report_base_item->is_success;

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

    public function remove_base_item($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_item->remove_base_item($id);

        $is_success = $this->my_report_base_item->is_success;
    
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

    public function update_base_item_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $item_kode = $req->data->item_kode;
        $item_name = $req->data->item_name;
        $last_used = $req->data->last_used;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional item_kode
        $valid_item_kode = !is_null($item_kode) && !empty($item_kode);
        if ($valid_item_kode) {
            $keyValueToUpdate["item_kode"] = $item_kode;
        }

        // conditional $item_name
        $valid_item_name = !is_null($item_name) && !empty($item_name);
        if ($valid_item_name) {
            $keyValueToUpdate["item_name"] = $item_name;
        }

        // conditional $last_used
        $valid_last_used = !is_null($last_used) && !empty($last_used);
        if ($valid_last_used) {
            $keyValueToUpdate["last_used"] = $last_used;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_base_item->update_base_item_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_base_item->is_success;
    
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
