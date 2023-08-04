<?php
require_once(__DIR__ . '/warehouse_model.php');

class My_report_warehouse
{
    protected $my_report_warehouse;
    function __construct()
    {
        $this->my_report_warehouse = new My_report_warehouse_model();
    }
    public function get_warehouses()
    { 
        $result = $this->my_report_warehouse->get_warehouses();
        
        if($this->my_report_warehouse->is_success) {
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
    public function add_warehouse()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $warehouse_name = $req->data->warehouse_name;
        $warehouse_group = $req->data->warehouse_group;
        $warehouse_supervisors = $req->data->warehouse_supervisors;

        $result = null;

        $is_request_body_oke = !is_null($warehouse_name) && !is_null($warehouse_group) && !is_null($warehouse_supervisors);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_warehouse->write_warehouse($id, $warehouse_name, $warehouse_group, $warehouse_supervisors);
            } else {
                // append warehouse
                $result = $this->my_report_warehouse->append_warehouse($warehouse_name, $warehouse_group, $warehouse_supervisors);
            }

            if($this->my_report_warehouse->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_warehouse->is_success
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
                'message' => 'Failed add warehouse, check the data you sent'
            ), 400
        );
    }
    public function get_warehouse_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_warehouse->get_warehouse_by_id($id);

        $is_success = $this->my_report_warehouse->is_success;

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
                    'message' => 'Warehouse not found'
                ), 404
            );
        }
    }

    // // public function deleteGuest($id) {
    // //     // myguest/8
    // //     // the 8 will automatically becoming parameter $id
    // //     return $this->my_report_warehouse->deleteGuest($id);
    // // }

    public function update_warehouse_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $warehouse_name = $req->data->warehouse_name;
        $warehouse_group = $req->data->warehouse_group;

        $invalid_request_body = is_null($warehouse_name) || is_null($warehouse_group) || is_null($id);

        if($invalid_request_body) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update warehouse, check the data you sent'
                ), 400
            );
            return;
        }

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional warehouse_name
        if ($warehouse_name) {
            $keyValueToUpdate["warehouse_name"] = $warehouse_name;
        }

        // conditional warehouse$warehouse_group
        if ($warehouse_group) {
            $keyValueToUpdate["warehouse_group"] = $warehouse_group;
        }

        $result = $this->my_report_warehouse->update_warehouse_by_id($keyValueToUpdate, "id", $id);

        $is_found = count($result) > 0;

        $is_success = $this->my_report_warehouse->is_success;

        if($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Update warehouse success'
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
                    'message' => 'Warehouse not found'
                ),
                404
            );
        }
        
    }

    public function get_last_id() {
        
        $lastId = $this->my_report_warehouse->last_id();
        Flight::json(
            array(
                'success' => false,
                'id' => $lastId
            )
        );
    }
}
