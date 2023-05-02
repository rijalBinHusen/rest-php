<?php
// require_once(__DIR__ . '/database.php');
// require_once(__DIR__ . '/generator_id.php');

// class My_report_field_problem_model
// {
//     protected $database;

//     var $table = "my_report_field_problem";
//     var $columns = "id, supervisor_id, head_spv_id, masalah, sumber_masalah, solusi, pic, dl";

//     function __construct()
//     {
//         $this->database = new sqldatabase();
//     }
//     // get problem between two periode
//     // public function get_problem_between_periode($periode1, $periode2)
//     // {
//     //     $sql = "SELECT 
//     //         problem.id, 
//     //         warehouse.warehouse_name, 
//     //         base_item.item_name, 
//     //         problem.masalah,
//     //         problem.tanggal_mulai, 
//     //         supervisor.supervisor_name,
//     //         problem.tanggal_selesai
//     //         FROM problem 
//     //         INNER JOIN warehouse ON problem.warehouse_id=warehouse.id 
//     //         INNER JOIN base_item ON problem.item_kode=base_item.item_kode 
//     //         INNER JOIN supervisor ON problem.supervisor_id=supervisor.id
//     //         WHERE problem.tanggal_mulai >= $periode1 AND problem.tanggal_mulai <= $periode2
//     //         ";
//     //     try {
//     //         // variabel that would contain result
//     //         $result = array();
//     //         // Prepare statement
//     //         $stmt = $this->database->conn->prepare($sql);
//     //         // execute the statement
//     //         $stmt->execute();
//     //         // fetch the statement and extract row
//     //         while ($row = $stmt->fetch()) {
//     //             array_push($result, array(
//     //                 'id' => $row['id'],
//     //                 'warehouse' => $row['warehouse_name'],
//     //                 'item' => $row['item_name'],
//     //                 'masalah' => $row['masalah'],
//     //                 'tanggal_mulai' => $row['tanggal_mulai'],
//     //                 'supervisor' => $row['supervisor_name'],
//     //                 'tanggal_selesai' => $row['tanggal_selesai'],
//     //             ));
//     //         }
//     //         // return $sql;
//     //         return $result;
//     //     } catch (PDOException $e) {
//     //         $this->database->log_error("get problem active", "problem", $e->getMessage());
//     //         return false;
//     //     }
//     // }
//     // get active problem
//     public function get_field_problems()
//     {
//     //     /* data that we need:
//     //         periode, supervisor.name, head_spv.name, masalah
//     //     */
//         $sql = "SELECT 
//             my_report_field_problem.id, 
//             my_report_field_problem.periode,
//             supervisor.supervisor_name,
//             head_spv.head_name
//             my_report_field_problem.masalah,
//             FROM my_report_field_problem 
//             INNER JOIN supervisor ON my_report_field_problem.supervisor_id=supervisor.id
//             INNER JOIN head_spv ON my_report_field_problem.head_spv_id=head_spv.id
//             LIMIT 100
//             ";
//         try {
//             // variabel that would contain result
//             $result = array();
//             // Prepare statement
//             $stmt = $this->database->conn->prepare($sql);
//             // execute the statement
//             $stmt->execute();
//             // fetch the statement and extract row
//             while ($row = $stmt->fetch()) {
//                 array_push($result, array(
//                     'id' => $row['id'],
//                     'periode' => $row['periode'],
//                     'supervisor_name' => $row['supervisor_name'],
//                     'head_name' => $row['head_name'],
//                     'masalah' => $row['masalah'],
//                 ));
//             }
//             // return $sql;
//             return $result;
//         } catch (PDOException $e) {
//             $this->database->log_error("get problem active", "problem", $e->getMessage());
//             return false;
//         }
//     }
//     // create new problem
//     public function append_field_problem(
//         $id,
//         $supervisor_id,
//         $head_spv_id,
//         $masalah,
//         $sumber_masalah,
//         $solusi,
//         $pic,
//         $dl
//     ) {
//         $nextId = $id;
//         if(is_null($id)) {
//             $lastId = $this->database->getLastId($this->table);
//             // jika tidak ada last id
//             $nextId = $lastId ? generateId($lastId['id']) : generateId('SUP22320000');
//         }
//         // the values
        
//         $values = "
//                     '$nextId',
//                     '$supervisor_id', 
//                     '$head_spv_id',
//                     '$masalah',
//                     '$sumber_masalah',
//                     '$solusi',
//                     '$pic',
//                     '$dl'
//                     ";
//         // send to database model
//         $res = $this->database->writeData($this->table, $this->columns, $values);
//         // ternary either success or fail
//         return $res
//             ? $nextId
//             : 'Can not insert to database';
//     }
//     public function delete_field_problem_by_id($id) {
//         return Flight::json(array(
//             'status' => $this->database->deleteData($this->table, 'id', $id)
//         ));
//     }
//     // get problem by id
//     public function get_field_problem_by_id($id)
//     {   
//         $sql = "SELECT 
//             my_report_field_problem.id, 
//             my_report_field_problem.periode,
//             supervisor.supervisor_name,
//             head_spv.head_name
//             my_report_field_problem.masalah,
//             my_report_field_problem.sumber_masalah,
//             my_report_field_problem.solusi,
//             my_report_field_problem.pic,
//             my_report_field_problem.dl
//             FROM my_report_field_problem 
//             INNER JOIN supervisor ON my_report_field_problem.supervisor_id=supervisor.id
//             INNER JOIN head_spv ON my_report_field_problem.head_spv_id=head_spv.id
//             WHERE my_report_field_problem.id = $id
//             ";
//         try {
//             // variabel that would contain result
//             $result = array();
//             // Prepare statement
//             $stmt = $this->database->conn->prepare($sql);
//             // execute the statement
//             $stmt->execute();
//             // fetch the statement and extract row
//             while ($row = $stmt->fetch()) {
//                 array_push($result, array(
//                     'id' => $row['id'],
//                     'periode' => $row['periode'],
//                     'supervisor_name' => $row['supervisor_name'],
//                     'head_name' => $row['head_name'],
//                     'masalah' => $row['masalah'],
//                     'sumber_masalah' => $row['sumber_masalah'],
//                     'solusi' => $row['solusi'],
//                     'pic' => $row['pic'],
//                     'dl' => $row['dl']
//                 ));
//             }
//             // return $sql;
//             return $result;
//         } catch (PDOException $e) {
//             $this->database->log_error("get problem active", "problem", $e->getMessage());
//             return false;
//         }
//     }
//     // // update problem by id
//     public function update_field_problem_by_id($keyValueToUpdate, $id)
//     {
//         $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
//         return $res;
//     }
//     public function get_problem_by_item_kode($item_kode)
//     {
//         $sql = "SELECT 
//             problem.id, 
//             warehouse.warehouse_name, 
//             base_item.item_name, 
//             problem.masalah,
//             problem.tanggal_mulai, 
//             supervisor.supervisor_name,
//             problem.tanggal_selesai
//             FROM problem 
//             INNER JOIN warehouse ON problem.warehouse_id=warehouse.id 
//             INNER JOIN base_item ON problem.item_kode=base_item.item_kode 
//             INNER JOIN supervisor ON problem.supervisor_id=supervisor.id
//             WHERE problem.item_kode = '$item_kode'
//             ";
//         try {
//             // variabel that would contain result
//             $result = array();
//             // Prepare statement
//             $stmt = $this->database->conn->prepare($sql);
//             // execute the statement
//             $stmt->execute();
//             // fetch the statement and extract row
//             while ($row = $stmt->fetch()) {
//                 array_push($result, array(
//                     'id' => $row['id'],
//                     'warehouse' => $row['warehouse_name'],
//                     'item' => $row['item_name'],
//                     'masalah' => $row['masalah'],
//                     'tanggal_mulai' => $row['tanggal_mulai'],
//                     'supervisor' => $row['supervisor_name'],
//                     'tanggal_selesai' => $row['tanggal_selesai'],
//                 ));
//             }
//             // return $sql;
//             return $result;
//         } catch (PDOException $e) {
//             $this->database->log_error("get problem active", "problem", $e->getMessage());
//             return false;
//         }
//     }
// }
