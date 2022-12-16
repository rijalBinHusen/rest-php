<?php
require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/generator_id.php');

class My_report_warehouse_model
{
    protected $database;

    var $table = "warehouse";
    var $columns = "id, warehouse_name, warehouse_group";

    function __construct()
    {
        $this->database = new sqldatabase();
    }
    public function get_warehouses()
    {
        return Flight::json(array(
            'status' => 'success',
            'data' => $this->database->getData($this->columns, $this->table)
        ));
    }
    public function append_warehouse($warehouse_name, $warehouse_group)
    {
        $lastId = $this->database->getLastId($this->table);
        // jika tidak ada last id
        $nextId = $lastId ? generateId($lastId['id']) : generateId('WAR22320000');
        // send to database model
        $res = $this->database->writeData($this->table, "( id,  warehouse_name, warehouse_group )", 
                "('$nextId', '$warehouse_name', '$warehouse_group'");
        // ternary either success or fail
        $result = $res ? 
                    $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$nextId'") 
                    : 'Can not insert to database';
        // return as json
        return Flight::json(array(
            'status' => $result
        ));
    }
    // public function deleteGuest($id) {
    //     return Flight::json(array(
    //         'status' => $this->database->deleteData($this->table, 'id', $id)
    //     ));
    // }
    public function get_warehous_by_id($id) {
        return Flight::json(array(
            'status' => $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'")
        ));
    }
    public function update_warehouse_by_id($keyValueToUpdate, $id) {
        $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
        return Flight::json(array(
            'status' => $res
        ));
    }
}
