<?php
// require_once(__DIR__ . '/database.php');
// require_once(__DIR__ . '/generator_id.php');

// class My_report_base_file_model
// {
//     protected $database;

//     var $table = "my_report_base_report_file";
//     var $columns = "id, periode, warehouse_id, file_name, stock_sheet, clock_sheet, is_imported";

//     function __construct()
//     {
//         $this->database = new sqldatabase();
//     }
//     // public function get_items()
//     // {
//     //     return $this->database->getAllData($this->columns, $this->table);
//     // }
//     public function append_base_file($id, $periode, $warehouse_id)
//     {
//         $nextId = $id;
//         if(is_null($id)) {
//             $lastId = $this->database->getLastId($this->table);
//             // jika tidak ada last id
//             $nextId = $lastId ? generateId($lastId['id']) : generateId('BFL22320000');
//         }
//         // send to database model
//         $res = $this->database->writeData(
//             $this->table,
//             "$this->columns",
//             "'$nextId', '$periode', '$warehouse_id', '', '', '', 0"
//         );
//         // ternary either success or fail
//         return $res
//                     ? array( 'id' => $nextId)
//                     : 'Can not insert to database';
//     }
//     public function delete_base_file($id) {
//         return $this->database->deleteData($this->table, 'id', "'$id'");
//     }
//     public function get_base_file_by_id($id) {
//         $res = $this->database->findDataByColumnCriteria($this->table, $this->columns, 'id', "'$id'");
//         return $res;
//     }
//     public function update_base_file_by_id($keyValueToUpdate, $id)
//     {
//         $res = $this->database->updateDataByCriteria($this->table, $keyValueToUpdate, 'id', "'$id'");
//         return $res;
//     }
//     public function get_base_files_between_two_periode($periode1, $periode2) {
//         // $database = new sqldatabase()
//         $res = $this->database->get_data_by_where_query($this->columns, $this->table, "periode >= $periode1 AND periode <= $periode2");
//         return $res;
//     }
// }
