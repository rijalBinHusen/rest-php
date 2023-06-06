<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_complain_model
{
    protected $database;
    var $table = "my_report_complain";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
      
        $this->summary =  SummaryDatabase::getInstance($this->table);
    }

    public function get_complains($limit)
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

    public function append_complain($periode, $head_spv_id, $dl, $inserted, $masalah, $supervisor_id, $parent, $pic, $solusi, $is_status_done, $sumber_masalah, $type, $is_count)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_complain(
            $nextId,
            $periode,
            $head_spv_id,
            $dl,
            $inserted,
            $masalah,
            $supervisor_id,
            $parent,
            $pic,
            $solusi,
            $is_status_done,
            $sumber_masalah,
            $type,
            $is_count
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function get_complain_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
            return array();
        } else {
            return $result;
        }

    }

    public function update_complain_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_complain($id, $periode, $head_spv_id, $dl, $inserted, $masalah, $supervisor_id, $parent, $pic, $solusi, $is_status_done, $sumber_masalah, $type, $is_count)
    {

        $data_to_insert = array(
            "id" => $id,
            'periode' => $periode,
            'head_spv_id' => $head_spv_id,
            'dl' => $dl,
            'inserted' => $inserted,
            'masalah' => $masalah,
            'supervisor_id' => $supervisor_id,
            'parent' => $parent,
            'pic' => $pic,
            'solusi' => $solusi,
            'is_status_done' => $is_status_done,
            'sumber_masalah' => $sumber_masalah,
            'type' => $type,
            'is_count' => $is_count,
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function remove_complain($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
