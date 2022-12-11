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
    // public function getMyGuests()
    // {
    //     return Flight::json(array(
    //         'status' => 'success',
    //         'data' => $this->database->getData($this->columns, $this->table)
    //     ));
    // }
    // public function append_warehouse($warehouse_name, $warehouse_group)
    public function append_warehouse()
    {
        // variable that will contain next id
        $nextId = null;
        $lastId = $this->database->getLastId($this->table);
        // jika tidak ada last id
        if ($lastId) {
            $nextId = generateId($lastId);
        } else {
            $nextId = generateId('WAR22050000');
        }
        // jika ada last id
        // $lastId;
        // $generatedId = 
        // $res = $this->database->writeData($this->table, "( warehouse_name, warehouse_group )", "( '" . $warehouse_name . "', '" . $warehouse_group . "'");
        return Flight::json(array(
            'status' => $nextId,
            // $res
        ));
    }
    // public function deleteGuest($id) {
    //     return Flight::json(array(
    //         'status' => $this->database->deleteData($this->table, 'id', $id)
    //     ));
    // }
    // public function getGuestById($id) {
    //     return Flight::json(array(
    //         'status' => $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', $id)
    //     ));
    // }
    // public function updateGuestById($keyValueToUpdate, $id) {
    //     $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', $id);
    //     return Flight::json(array(
    //         'status' => $res
    //     ));
    // }
    public function getLastId()
    {
    }
}
