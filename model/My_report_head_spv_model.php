<?php
// require_once(__DIR__ . '/database.php');
// require_once(__DIR__ . '/generator_id.php');

// class My_report_head_spv_model
// {
//     protected $database;

//     var $table = "my_report_head_spv";
//     var $columns = "id, head_name, head_phone, head_shift, is_disabled";

//     function __construct()
//     {
//         $this->database = new sqldatabase();
//     }
//     public function get_heads()
//     {
//         return $this->database->getAllData($this->columns, $this->table);
//     }
//     public function append_head($head_name, $head_phone, $head_shift, $is_disabled)
//     {
//         $lastId = $this->database->getLastId($this->table);
//         // jika tidak ada last id
//         $nextId = $lastId ? generateId($lastId['id']) : generateId('HEA22320000');
//         // send to database model
//         $res = $this->database->writeData(
//             $this->table,
//             "id,  head_name, head_phone, head_shift, is_disabled",
//             "'$nextId', '$head_name', '$head_phone', '$head_shift', '$is_disabled'"
//         );
//         // ternary either success or fail
//         return $res
//             ? $nextId
//             : 'Can not insert to database';
//     }
//     // public function deleteGuest($id) {
//     //     return Flight::json(array(
//     //         'status' => $this->database->deleteData($this->table, 'id', $id)
//     //     ));
//     // }
//     public function get_head_spv_by_id($id)
//     {
//         $res = $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'");
//         return $res;
//     }
//     public function update_head_spv_by_id($keyValueToUpdate, $id)
//     {
//         $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
//         return $res;
//     }
//     public function write_head($id, $head_name, $head_phone, $head_shift, $is_disabled)
//     {
//         // send to database model
//         $res = $this->database->writeData(
//             $this->table,
//             "id,  head_name, head_phone, head_shift, is_disabled",
//             "'$id', '$head_name', '$head_phone', '$head_shift', '$is_disabled'"
//         );
//         // ternary either success or fail
//         return $res ? true : false;
//     }
// }
