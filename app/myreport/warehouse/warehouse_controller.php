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
        return Flight::json($result, 200);
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

        if($warehouse_name && $warehouse_group && $warehouse_supervisors) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_warehouse->write_warehouse($id, $warehouse_name, $warehouse_group, $warehouse_supervisors);
            } else {
                // append warehouse
                $result = $this->my_report_warehouse->append_warehouse($warehouse_name, $warehouse_group, $warehouse_supervisors);
            }
            
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                ), 200
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
    // public function get_warehouse_by_id($id)
    // {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     $this->result_from_model = $this->my_report_warehouse->get_warehous_by_id($id);
    //     return $this->response();
    // }
    // // public function deleteGuest($id) {
    // //     // myguest/8
    // //     // the 8 will automatically becoming parameter $id
    // //     return $this->my_report_warehouse->deleteGuest($id);
    // // }
    // public function update_warehouse_by_id($id)
    // {
    //     // catch the query string request
    //     $req = Flight::request();
    //     $warehouse_name = $req->data->warehouse_name;
    //     $warehouse_group = $req->data->warehouse_group;
    //     // initiate the column and values to update
    //     $keyValueToUpdate = null;
    //     // conditional warehouse_name
    //     if ($warehouse_name) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "warehouse_name='$warehouse_name'"
    //             : "$keyValueToUpdate, warehouse_name='$warehouse_name'";
    //     }

    //     // conditional warehouse$warehouse_group
    //     if ($warehouse_group) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate)
    //             ? "warehouse_group='$warehouse_group'"
    //             : "$keyValueToUpdate, warehouse_group='$warehouse_group'";
    //     }
    //     // send to myguest model
    //     $this->result_from_model = $this->my_report_warehouse->update_warehouse_by_id($keyValueToUpdate, $id);
    //     return $this->response();
    // }
    // protected function response()
    // {
    //     if ($this->result_from_model) {
    //         // set the http status code 200
    //         $this->code = 200;
    //         // set the result data that would be return to user
    //         $this->result = $this->result_from_model;
    //     } else {
    //         // set the http status code 200
    //         $this->code = 400;
    //     }
    //     // return the result
    //     return Flight::json(
    //         // the result
    //         $this->result
    //         // and the code
    //         ,
    //         $this->code
    //     );
    // }
}