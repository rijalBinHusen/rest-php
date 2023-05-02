<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/generator_id.php');

class My_report_warehouse_model
{
    protected $database;

    var $table = "my_report_warehouse";
    var $columns = "id, warehouse_name, warehouse_group, warehouse_supervisors";

    function __construct()
    {
        $this->database = new Query_builder();
    }
    public function get_warehouses()
    {
        return $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function append_warehouse($warehouse_name, $warehouse_group, $warehouse_supervisors)
    {
        $lastIdQuery = "SELECT * FROM $this->table ORDER BY id DESC LIMIT 1";
        $lastId = $this->database->sqlQuery($lastIdQuery);
        // nextId
        $nextId = $lastId ? generateId($lastId['id']) : generateId('WAREHOUSE_22320000');
        // data to write to database
        $res = array(
            "id" => $nextId,
            'warehouse_name' => $warehouse_name,
            'warehouse_group' => $warehouse_group,
            'warehouse_supervisors' => $warehouse_supervisors
        );
        $this->database->insert($this->table, $res);
        return $nextId;
    }
    public function get_warehouse_by_id($id)
    {
        return $this->database->select_where($this->table, 'id', $id);
    }
    public function update_warehouse_by_id(array $data, $where, $id)
    {
        return $this->database->update($this->table, $data, $where, $id);
    }
    public function write_warehouse(array $data)
    {
        return $this->database->insert($this->table, $data);
    }
}
