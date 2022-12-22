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
    // public function get_problem_active()
    // {
    //     /* data that we need:
    //         id, warehouse, item_name, masalah, tanggal_mulai, supervisor
    //     */
    //     // table that we need to join from another
    //     $warehouse = "warehouse.warehouse_name";
    //     $item = "base_item.item_name";
    //     $supervisor = "supervisor.supervisor_name";
    //     $problem = "problem.id, problem.masalah, problem.tanggal_mulai";
    //     // supervisor.id, warehouse.id, head_spv.id
    //     $sql = "SELECT $problem, $warehouse, $item, $supervisor
    //     FROM problem
    //     INNER JOIN warehouse ON problem.warehouse_id=warehouse.id
    //     INNER JOIN item ON problem.item_kode=base_item.item_kode
    //     INNER JOIN supervisor ON problem.supervisor_id=supervisor.id
    //     ";
    //     // variabel that would contain result
    //     $result = array();
    //     // split the column string as array
    //     $arrOfColumns = explode(", ", $this->columns);
    //     try {
    //         // Prepare statement
    //         $stmt = $this->database->conn->prepare($sql);
    //         // execute the statement
    //         $stmt->execute();
    //         // fetch the statement and extract row
    //         while ($row = $stmt->fetch()) {
    //             // $tempResult = array();
    //             // // iterate the columns
    //             // foreach ($arrOfColumns as $column) {
    //             //     // tempResult { tempResult: row }
    //             //     $tempResult[$column] = $row[$column];
    //             // }
    //             // push to result
    //             array_push($result, $row);
    //         }
    //         // return $sql;
    //         return $result;
    //     } catch (PDOException $e) {
    //         $this->database->log_error("get problem active", "problem", $e->getMessage());
    //         return false;
    //     }
    //     return $this->database->get_data_by_where_query($this->columns, $this->table, "tanggal_selesai=null");
    // }
    // create new problem
    public function append_problem(
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
        $shift_selesai
    ) {
        $nextId = $id;
        if(is_null($id)) {
            $lastId = $this->database->getLastId($this->table);
            // jika tidak ada last id
            $nextId = $lastId ? generateId($lastId['id']) : generateId('SUP22320000');
        }
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
