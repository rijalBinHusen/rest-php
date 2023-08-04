<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_problem_model
{
    protected $database;
    var $table = "my_report_problem";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
      
        $this->summary = SummaryDatabase::getInstance($this->table);
    }

    public function append_problem(
        $warehouse_id,
        $supervisor_id,
        $head_spv_id,
        $item_kode,
        $tanggal_mulai,
        $shift_mulai,
        $pic,
        $dl,
        $masalah,
        $sumber_masalah,
        $solusi,
        $solusi_panjang,
        $dl_panjang,
        $pic_panjang,
        $tanggal_selesai,
        $shift_selesai,
        $is_finished
    )
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_problem(
            $nextId,
            $warehouse_id,
            $supervisor_id,
            $head_spv_id,
            $item_kode,
            $tanggal_mulai,
            $shift_mulai,
            $pic,
            $dl,
            $masalah,
            $sumber_masalah,
            $solusi,
            $solusi_panjang,
            $dl_panjang,
            $pic_panjang,
            $tanggal_selesai,
            $shift_selesai,
            $is_finished
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function write_problem(
        $id, 
        $warehouse_id,
        $supervisor_id,
        $head_spv_id,
        $item_kode,
        $tanggal_mulai,
        $shift_mulai,
        $pic,
        $dl,
        $masalah,
        $sumber_masalah,
        $solusi,
        $solusi_panjang,
        $dl_panjang,
        $pic_panjang,
        $tanggal_selesai,
        $shift_selesai,
        $is_finished
        )
    {

        $data_to_insert = array(
            "id" => $id,
            'warehouse_id' => $warehouse_id,
            'supervisor_id' => $supervisor_id,
            'head_spv_id' =>$head_spv_id,
            'item_kode' => $item_kode,
            'tanggal_mulai' => $tanggal_mulai,
            'shift_mulai' => $shift_mulai,
            'pic' => $pic,
            'dl' => $dl,
            'masalah' => $masalah,
            'sumber_masalah' => $sumber_masalah,
            'solusi' => $solusi,
            'solusi_panjang' => $solusi_panjang,
            'dl_panjang' => $dl_panjang,
            'pic_panjang' => $pic_panjang,
            'tanggal_selesai' => $tanggal_selesai,
            'shift_selesai' => $shift_selesai,
            'is_finished' => $is_finished
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function get_problem_by_periode($periode1, $periode2)
    {
        $query = "SELECT * FROM $this->table where tanggal_mulai BETWEEN $periode1 AND $periode2";
        $result  = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        else {

            return $result;

        }
    }

    public function get_problem_by_status($status)
    {
        $result  = $this->database->select_where($this->table, 'is_finished', $status)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }

        else {

            return $result;

        }
    }

    public function get_problem_by_supervisor($supervisor)
    {
        $result  = $this->database->select_where($this->table, 'supervisor_id', $supervisor)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        
        else {

            return $result;

        }
    }

    public function get_problem_by_warehouse_and_item($warehouse, $item)
    {
        $query = "SELECT * FROM $this->table WHERE warehouse_id = '$warehouse' AND item_kode = '$item'";
        $result  = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        
        else {

            return $result;

        }
    }

    public function get_problem_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_problem_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            if($result == 0) {
                $query = "SELECT EXISTS(SELECT id FROM $this->table WHERE id = '$id')";
                return $this->database->sqlQuery($query)->fetchColumn();
            }

            return $result;

        }

    }
}
