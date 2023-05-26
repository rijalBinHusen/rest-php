<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_field_problem_model
{
    protected $database;
    var $table = "my_report_field_problem";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = new Query_builder();
        $this->summary = new SummaryDatabase($this->table);
    }

    public function get_field_problems($limit)
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

    public function append_field_problem($periode, $supervisor_id, $head_spv_id, $masalah, $sumber_masalah, $solusi, $pic, $dl)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_field_problem(
            $nextId,
            $periode,
            $supervisor_id,
            $head_spv_id,
            $masalah,
            $sumber_masalah,
            $solusi,
            $pic,
            $dl
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function get_field_problem_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_field_problem_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_field_problem($id, $periode, $supervisor_id, $head_spv_id, $masalah, $sumber_masalah, $solusi, $pic, $dl)
    {

        $data_to_insert = array(
            "id" => $id,
            'periode' => $periode,
            'supervisor_id' => $supervisor_id,
            'head_spv_id' => $head_spv_id,
            'masalah' => $masalah,
            'sumber_masalah' => $sumber_masalah,
            'solusi' => $solusi,
            'pic' => $pic,
            'dl' => $dl
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function remove_field_problem($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
