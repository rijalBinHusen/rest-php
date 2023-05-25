<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_warehouse_model
{
    protected $database;
    var $table = "my_report_warehouse";
    var $columns = "id, warehouse_name, warehouse_group, warehouse_supervisors";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = new Query_builder();
        $this->summary = new SummaryDatabase($this->table);
    }

    public function get_warehouses()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        }
        else {
            return $result;
        }
    }

    public function append_warehouse($warehouse_name, $warehouse_group, $warehouse_supervisors)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_warehouse($nextId, $warehouse_name, $warehouse_group, $warehouse_supervisors);

        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        } else {
            return $nextId;
        }

    }

    public function get_warehouse_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
            return array();
        } else {
            return $result;
        }

    }

    public function update_warehouse_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        } else {
            return $result;
        }

    }

    public function write_warehouse($id, $warehouse_name, $warehouse_group, $warehouse_supervisors)
    {
        $data = array(
            "id" => $id,
            'warehouse_name' => $warehouse_name,
            'warehouse_group' => $warehouse_group,
            'warehouse_supervisors' => $warehouse_supervisors
        );

        $this->database->insert($this->table, $data);

        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        } else {
            $this->summary->updateLastId($id);
            return $id;
        }

    }

    public function last_id()
    {
        return $this->summary->getLastId();
    }
}
