<?php
require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/generator_id.php');

class My_report_warehouse_model
{
    protected $database;

    var $table = "my_report_warehouse";
    var $columns = "id, warehouse_name, warehouse_group, warehouse_supervisors";

    function __construct()
    {
        $this->database = new sqldatabase();
    }
    public function get_warehouses()
    {
        return $this->database->getData($this->columns, $this->table);
    }
    public function append_warehouse($warehouse_name, $warehouse_group, $warehouse_supervisors)
    {
        $lastId = $this->database->getLastId($this->table);
        // jika tidak ada last id
        $nextId = $lastId ? generateId($lastId['id']) : generateId('WAR22320000');
        // send to database model
        $res = $this->database->writeData(
            $this->table,
            $this->columns,
            "'$nextId', '$warehouse_name', '$warehouse_group', $warehouse_supervisors"
        );
        // ternary either success or fail
        return $res ? $nextId : 'Can not insert to database';
    }
    // public function deleteGuest($id) {
    //     return Flight::json(array(
    //         'status' => $this->database->deleteData($this->table, 'id', $id)
    //     ));
    // }
    public function get_warehous_by_id($id)
    {
        $res = $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'");
        return $res;
    }
    public function update_warehouse_by_id($keyValueToUpdate, $id)
    {
        $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
        return $res;
    }
    public function write_warehouse($id, $warehouse_name, $warehouse_group, $warehouse_supervisors)
    {
        // send to database model
        $res = $this->database->writeData(
            $this->table,
            $this->columns,
            "'$id', '$warehouse_name', '$warehouse_group', '$warehouse_supervisors'"
        );
        // ternary either success or fail
        return $res ? true : false;
    }
}
