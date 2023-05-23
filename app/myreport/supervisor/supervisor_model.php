<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_supervisor_model
{
    protected $database;
    var $table = "my_report_supervisor";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = new Query_builder();
        $this->summary = new SummaryDatabase($this->table);
    }

    public function get_supervisors()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        }
        else {
            return $result;
        }
    }

    public function append_supervisor($supervisor_name, $supervisor_phone, $supervisor_warehouse, $supervisor_shift, $is_disabled)
    {
        $nextId = $this->summary->getNextId();
        // data to write to database
        $res = array(
            "id" => $nextId,
            'supervisor_name' => $supervisor_name,
            'supervisor_phone' => $supervisor_phone,
            'supervisor_warehouse' => $supervisor_warehouse,
            'supervisor_shift' => $supervisor_shift,
            'is_disabled' => $is_disabled
        );

        $this->database->insert($this->table, $res);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($nextId);
            return $nextId;

        }

    }

    public function get_supervisor_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);;
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
            return array();
        } else {
            return $result;
        }

    }

    public function update_supervisor_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_supervisor(array $data)
    {
        $this->database->insert($this->table, $data);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($data['id']);
            return $data['id'];

        }

    }
}
