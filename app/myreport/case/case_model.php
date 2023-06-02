<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_case_model
{
    protected $database;
    var $table = "my_report_cases";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $connection_db = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
        $connection_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->database = Query_builder::getInstance($connection_db);
      
        $this->summary = new SummaryDatabase($this->table);
    }

    public function get_cases($limit)
    {
        $query = "SELECT * FROM $this->table ORDER BY id DESC LIMIT $limit";
        $result  = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        }
        else {
            return $result;
        }
    }

    public function append_case($periode, $head_spv_id, $dl, $masalah, $supervisor_id, $parent, $pic, $solusi, $status, $sumber_masalah)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_case(
            $nextId,
            $periode,
            $head_spv_id,
            $dl,
            $masalah,
            $supervisor_id,
            $parent,
            $pic,
            $solusi,
            $status,
            $sumber_masalah
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function get_case_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_case_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_case($id, $periode, $head_spv_id, $dl, $masalah, $supervisor_id, $parent, $pic, $solusi, $status, $sumber_masalah)
    {

        $data_to_insert = array(
            "id" => $id,
            'periode' => $periode,
            'head_spv_id' => $head_spv_id,
            'dl' => $dl,
            'masalah' => $masalah,
            'supervisor_id' => $supervisor_id,
            'parent' => $parent,
            'pic' => $pic,
            'solusi' => $solusi,
            'status' => $status,
            'sumber_masalah' => $sumber_masalah
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function remove_case($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
