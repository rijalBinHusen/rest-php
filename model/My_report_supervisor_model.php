<?php
require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/generator_id.php');

class My_report_supervisor_model
{
    protected $database;

    var $table = "supervisor";
    var $columns = "id, supervisor_name, supervisor_phone, supervisor_warehouse, supervisor_shift, is_disabled";

    function __construct()
    {
        $this->database = new sqldatabase();
    }
    public function get_supervisors()
    {
        return $this->database->getAllData($this->columns, $this->table);
    }
    public function append_supervisor($supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled)
    {
        $lastId = $this->database->getLastId($this->table);
        // jika tidak ada last id
        $nextId = $lastId ? generateId($lastId['id']) : generateId('SUP22320000');
        // send to database model
        $res = $this->database->writeData(
            $this->table,
            "( id,  supervisor_name, supervisor_phone, supervisor_warehouse, supervisor_shift, is_disabled )",
            "('$nextId', '$supervisor_name', '$supervisor_phone', '$supervisor_warehouse', '$supervisor_shift', '$is_disabled'"
        );
        // ternary either success or fail
        return $res
                    ? $nextId
                    : 'Can not insert to database';
    }
    // public function deleteGuest($id) {
    //     return Flight::json(array(
    //         'status' => $this->database->deleteData($this->table, 'id', $id)
    //     ));
    // }
    public function get_supervisor_by_id($id) {
        $res = $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'");
        return $res;
    }
    public function update_supervisor_by_id($keyValueToUpdate, $id)
    {
        $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
        return $res;
    }
}
