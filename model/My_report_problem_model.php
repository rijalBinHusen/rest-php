<?php
require_once(__DIR__ . '/database.php');
require_once(__DIR__ . '/generator_id.php');

class My_report_problem_model
{
    protected $database;

    var $table = "problem";
    var $columns = "id, 
                    warehouse_id, 
                    supervisor_id, 
                    head_spv_id, 
                    item_kode, 
                    tanggal_mulai,
                    shift_mulai,
                    pic,
                    dl,
                    masalah,
                    sumber_masalah,
                    solusi,
                    solusi_panjang,
                    dl_panjang,
                    pic_panjang,
                    tanggal_selesai,
                    shift_selesai
                    ";

    function __construct()
    {
        $this->database = new sqldatabase();
    }
    // get problem between two periode
    public function get_problem_between_periode($periode1, $periode2)
    {
        return $this->database->get_data_by_where_query($this->columns, $this->table, "tanggal_mulai >= $periode1 AND tanggal_mulai <= $periode2");
    }
    // get active problem
    public function get_problem_active()
    {
        return $this->database->get_data_by_where_query($this->columns, $this->table, "tanggal_selesai=null");
    }
    // create new problem
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
        $shift_selesai
    ) {
        $lastId = $this->database->getLastId($this->table);
        // jika tidak ada last id
        $nextId = $lastId ? generateId($lastId['id']) : generateId('SUP22320000');
        // the values
        $values = "
                    '$nextId', 
                    '$warehouse_id', 
                    '$supervisor_id', 
                    '$head_spv_id', 
                    '$item_kode', 
                    '$tanggal_mulai',
                    '$shift_mulai',
                    '$pic',
                    '$dl',
                    '$masalah',
                    '$sumber_masalah',
                    '$solusi',
                    '$solusi_panjang',
                    '$dl_panjang',
                    '$pic_panjang',
                    '$tanggal_selesai',
                    '$shift_selesai'
                    ";
        // send to database model
        $res = $this->database->writeData($this->table, $this->columns, $values);
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
    // get problem by id
    public function get_problem_by_id($id)
    {
        $res = $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'");
        return $res;
    }
    // update problem by id
    public function update_problem_by_id($keyValueToUpdate, $id)
    {
        $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
        return $res;
    }
}
