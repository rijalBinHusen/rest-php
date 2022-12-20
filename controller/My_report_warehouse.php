<?php
require_once(__DIR__ . '/../model/My_report_warehouse_model.php');

class My_report_warehouse
{
    protected $my_report_warehouse;
    function __construct()
    {
        $this->my_report_warehouse = new My_report_warehouse_model();
    }
    public function get_warehouses()
    {
        return $this->my_report_warehouse->get_warehouses();
    }
    public function add_warehouse()
    {
        $req = Flight::request();
        $warehouse_name = $req->data->warehouse_name;
        $warehouse_group = $req->data->warehouse_group;
        return $this->my_report_warehouse->append_warehouse($warehouse_name, $warehouse_group);
    }
    public function get_warehouse_by_id($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        return $this->my_report_warehouse->get_warehous_by_id($id);
    }
    // public function deleteGuest($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     return $this->my_report_warehouse->deleteGuest($id);
    // }
    public function update_warehouse_by_id($id) {
        // catch the query string request
        $req = Flight::request();
        $warehouse_name = $req->data->warehouse_name;
        $warehouse_group = $req->data->warehouse_group;
        // initiate the column and values to update
        $keyValueToUpdate = null;
        // conditional warehouse_name
        if($warehouse_name) {
            $keyValueToUpdate = is_null($keyValueToUpdate) 
                ? "warehouse_name='$warehouse_name'" 
                : "$keyValueToUpdate, warehouse_name='$warehouse_name'";
        } 

        // conditional warehouse$warehouse_group
        if($warehouse_group) {
            $keyValueToUpdate = is_null($keyValueToUpdate) 
                ? "warehouse_group='$warehouse_group'" 
                : "$keyValueToUpdate, warehouse_group='$warehouse_group'";
        }
        // send to myguest model
        $this->my_report_warehouse->update_warehouse_by_id($keyValueToUpdate, $id);
    }
}
