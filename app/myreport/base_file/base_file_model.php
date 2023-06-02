<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_base_file_model
{
    protected $database;
    var $table = "my_report_base_report_file";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $connection_db = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
        $connection_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->database = Query_builder::getInstance($connection_db);
      
        $this->summary = new SummaryDatabase($this->table);
    }

    public function get_base_files($periode1, $periode2)
    {
        $query = "SELECT * FROM $this->table WHERE periode BETWEEN $periode1 AND $periode2";
        $result  = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        else {
            return $result;
        }
    }

    public function append_base_file($periode, $warehouse_id, $file_name, $stock_sheet, $clock_sheet, $is_imported)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_base_file(
            $nextId,
            $periode,
            $warehouse_id,
            $file_name,
            $stock_sheet,
            $clock_sheet,
            $is_imported
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function get_base_file_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_base_file_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_base_file($id, $periode, $warehouse_id, $file_name, $stock_sheet, $clock_sheet, $is_imported)
    {

        $data_to_insert = array(
            "id" => $id,
            'periode' => $periode,
            'warehouse_id' => $warehouse_id,
            'file_name' => $file_name,
            'stock_sheet' => $stock_sheet,
            'clock_sheet' => $clock_sheet,
            'is_imported' => $is_imported
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function remove_base_file($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
