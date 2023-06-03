<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_head_spv_model
{
    protected $database;
    var $table = "my_report_head_spv";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $connection_db = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
        $connection_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->database = Query_builder::getInstance($connection_db);
      
        $this->summary = SummaryDatabase::getInstance($this->table);
    }

    public function get_heads_spv()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        }
        else {
            return $result;
        }
    }

    public function append_head_spv($head_name, $head_phone, $head_shift, $is_disabled)
    {
        $nextId = $this->summary->getNextId();
        // write to database

        $this->write_head_spv($nextId, $head_name, $head_phone, $head_shift, $is_disabled);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($nextId);
            return $nextId;

        }

    }

    public function get_head_spv_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
            return array();
        } else {
            return $result;
        }

    }

    public function update_head_spv_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_head_spv($id, $head_name, $head_phone, $head_shift, $is_disabled)
    {
        $data = array(
            "id" => $id,
            'head_name' => $head_name,
            'head_phone' => $head_phone,
            'head_shift' => $head_shift,
            'is_disabled' => $is_disabled
        );

        $this->database->insert($this->table, $data);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }
}
