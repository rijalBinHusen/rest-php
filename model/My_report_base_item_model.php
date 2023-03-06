<?php
require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/generator_id.php');

class My_report_base_item_model
{
    protected $database;

    var $table = "my_report_base_item";
    var $columns = "id, item_kode, item_name, last_used";

    function __construct()
    {
        $this->database = new sqldatabase();
    }
    public function get_items()
    {
        return $this->database->getAllData($this->columns, $this->table);
    }
    public function append_item($kode, $name, $last_used)
    {
        $lastId = $this->database->getLastId($this->table);
        // jika tidak ada last id
        $nextId = $lastId ? generateId($lastId['id']) : generateId('ITM22320000');
        // send to database model
        $res = $this->database->writeData(
            $this->table,
            "$this->columns",
            "'$nextId', '$kode', '$name', '$last_used'"
        );
        // ternary either success or fail
        return $res
                    ? array( 'id' => $nextId)
                    : 'Can not insert to database';
    }
    public function delete_item($id) {
        return $this->database->deleteData($this->table, 'id', "'$id'");
    }
    public function get_item_by_id($id) {
        $res = $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'");
        return $res;
    }
    public function update_item_by_id($keyValueToUpdate, $id)
    {
        $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
        return $res;
    }
    public function write_item($id, $kode, $name, $last_used)
    {
        $res = $this->database->writeData(
            $this->table,
            "$this->columns",
            "'$id', '$kode', '$name', '$last_used'"
        );
        // ternary either success or fail
        return $res
                    ? true
                    : false;
    }
}
