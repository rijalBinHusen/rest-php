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
    public function get_problem_actives()
    {
    //     /* data that we need:
    //         id, warehouse, item_name, masalah, tanggal_mulai, supervisor
    //     */
        $sql = "SELECT 
            problem.id, 
            warehouse.warehouse_name, 
            base_item.item_name, 
            problem.masalah,
            problem.tanggal_mulai, 
            supervisor.supervisor_name,
            problem.tanggal_selesai
            FROM problem 
            INNER JOIN warehouse ON problem.warehouse_id=warehouse.id 
            INNER JOIN base_item ON problem.item_kode=base_item.item_kode 
            INNER JOIN supervisor ON problem.supervisor_id=supervisor.id
            WHERE problem.tanggal_selesai = 0 
            ";
        try {
            // variabel that would contain result
            $result = array();
            // Prepare statement
            $stmt = $this->database->conn->prepare($sql);
            // execute the statement
            $stmt->execute();
            // fetch the statement and extract row
            while ($row = $stmt->fetch()) {
                array_push($result, array(
                    'id' => $row['id'],
                    'warehouse' => $row['warehouse_name'],
                    'item' => $row['item_name'],
                    'masalah' => $row['masalah'],
                    'tanggal_mulai' => $row['tanggal_mulai'],
                    'supervisor' => $row['supervisor_name'],
                    'tanggal_selesai' => $row['tanggal_selesai'],
                ));
            }
            // return $sql;
            return $result;
        } catch (PDOException $e) {
            $this->database->log_error("get problem active", "problem", $e->getMessage());
            return false;
        }
    }
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
        /*  SELECT 
            problem.id, 
            warehouse.warehouse_name, 
            supervisor.supervisor_name,
            head_spv.head_name,
            base_item.item_name, 
            problem.tanggal_mulai, 
            problem.shift_mulai,
            problem.pic,
            problem.dl,
            problem.masalah, 
            problem.sumber_masalah,
            problem.solusi,
            problem.solusi_panjang,
            problem.dl_panjang,
            problem.pic_panjang,
            problem.tanggal_selesai,
            problem.shift_selesai
            FROM problem 
            INNER JOIN warehouse ON problem.warehouse_id=warehouse.id 
            INNER JOIN base_item ON problem.item_kode=base_item.item_kode 
            INNER JOIN supervisor ON problem.supervisor_id=supervisor.id 
            INNER JOIN head_spv ON problem.head_spv_id=head_spv.id
            WHERE problem.id = 'PRB22050000' 
        */
        $sql = "
                SELECT 
                problem.id, 
                warehouse.warehouse_name, 
                supervisor.supervisor_name,
                head_spv.head_name,
                base_item.item_name, 
                problem.tanggal_mulai, 
                problem.shift_mulai,
                problem.pic,
                problem.dl,
                problem.masalah, 
                problem.sumber_masalah,
                problem.solusi,
                problem.solusi_panjang,
                problem.dl_panjang,
                problem.pic_panjang,
                problem.tanggal_selesai,
                problem.shift_selesai
                FROM problem 
                INNER JOIN warehouse ON problem.warehouse_id=warehouse.id 
                INNER JOIN base_item ON problem.item_kode=base_item.item_kode 
                INNER JOIN supervisor ON problem.supervisor_id=supervisor.id 
                INNER JOIN head_spv ON problem.head_spv_id=head_spv.id
                WHERE problem.id = '$id'
            ";
        try {
            // Prepare statement
            $stmt = $this->database->conn->prepare($sql);
            // execute the statement
            $stmt->execute();
            // fetch the statement and extract row
            $row = $stmt->fetch();
            // extract row
            $result = array(
                'id' => $row['id'],
                'warehouse' => $row['warehouse_name'],
                'supervisor' => $row['supervisor_name'],
                'head_spv' => $row['head_name'],
                'item' => $row['item_name'],
                'tanggal_mulai' => $row['tanggal_mulai'],
                'pic' => $row['pic'],
                'dl' => $row['dl'],
                'masalah' => $row['masalah'],
                'sumber_masalah' => $row['sumber_masalah'],
                'solusi' => $row['solusi'],
                'solusi_panjang' => $row['solusi_panjang'],
                'dl_panjang' => $row['dl_panjang'],
                'pic_panjang' => $row['pic_panjang'],
                'tanggal_selesai' => $row['tanggal_selesai'],
                'shift_selesai' => $row['shift_selesai'],
                'shift_mulai' => $row['shift_mulai'],
            );
            
            // return $sql;
            return $result;
        } catch (PDOException $e) {
            $this->database->log_error("get problem active", "problem", $e->getMessage());
            return false;
        }
    }
    // update problem by id
    public function update_problem_by_id($keyValueToUpdate, $id)
    {
        $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
        return $res;
    }
}
