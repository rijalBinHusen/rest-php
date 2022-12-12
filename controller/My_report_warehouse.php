<?php
require_once(__DIR__ . '/../model/My_report_warehouse_model.php');

class My_report_warehouse
{
    // protected $my_report_warehouse;
    function __construct()
    {
        $this->my_report_warehouse = new My_report_warehouse_model();
    }
    public function get_warehouse()
    {
        return $this->my_report_warehouse->get_warehouse();
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
    // public function updateGuestById($id) {
    //     // catch the query string request
    //     $req = Flight::request();
    //     $firstname = $req->query->firstname;
    //     $lastname = $req->query->lastname;
    //     $email = $req->query->email;
    //     // initiate the column and values to update
    //     $keyValueToUpdate = null;
    //     // conditional firstname
    //     if($firstname) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate) ? "firstname='$firstname'" : "$keyValueToUpdate firstname='$firstname'";
    //     } 

    //     // conditional lastname
    //     if($lastname) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate) ? "lastname='$lastname'" : "$keyValueToUpdate lastname='$lastname'";
    //     } 

    //     // conditional email
    //     if($email) {
    //         $keyValueToUpdate = is_null($keyValueToUpdate) ? "email='$email'" : "$keyValueToUpdate email='$email'";
    //     } 
    //     // send to myguest model
    //     $this->my_report_warehouse->updateGuestById($keyValueToUpdate, $id);
    // }
}
