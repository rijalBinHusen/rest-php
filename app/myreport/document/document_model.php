<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_document_model
{
    protected $database;
    var $table = "my_report_document";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $connection_db = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
        $connection_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->database = Query_builder::getInstance($connection_db);
      
        $this->summary = new SummaryDatabase($this->table);
    }

    public function append_document(
        $collected,
        $approval,
        $status,
        $shared,
        $finished,
        $total_do,
        $total_kendaraan,
        $total_waktu,
        $base_report_file,
        $is_finished,
        $supervisor_id,
        $periode,
        $shift,
        $head_spv_id,
        $warehouse_id,
        $is_generated_document
    )
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_document(
            $nextId,
            $collected,
            $approval,
            $status,
            $shared,
            $finished,
            $total_do,
            $total_kendaraan,
            $total_waktu,
            $base_report_file,
            $is_finished,
            $supervisor_id,
            $periode,
            $shift,
            $head_spv_id,
            $warehouse_id,
            $is_generated_document
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function write_document(
        $id,
        $collected,
        $approval,
        $status,
        $shared,
        $finished,
        $total_do,
        $total_kendaraan,
        $total_waktu,
        $base_report_file,
        $is_finished,
        $supervisor_id,
        $periode,
        $shift,
        $head_spv_id,
        $warehouse_id,
        $is_generated_document
    )
    {

        $data_to_insert = array(
            "id" => $id,
            'collected' => $collected,
            'approval' => $approval,
            'status' =>$status,
            'shared' => $shared,
            'finished' => $finished,
            'total_do' => $total_do,
            'total_kendaraan' => $total_kendaraan,
            'total_waktu' => $total_waktu,
            'base_report_file' => $base_report_file,
            'is_finished' => $is_finished,
            'supervisor_id' => $supervisor_id,
            'periode' => $periode,
            'shift' => $shift,
            'head_spv_id' => $head_spv_id,
            'warehouse_id' => $warehouse_id,
            'is_generated_document' => $is_generated_document
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function get_documents_by_periode($periode1, $periode2)
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

    public function get_documents_by_status($status)
    {
        $result  = $this->database->select_where($this->table, 'status', $status)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }

        else {

            return $result;

        }
    }
    
    public function get_document_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_document_by_id(array $data, $id)
    {

        $result = $this->database->update($this->table, $data, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function remove_document_by_id($id)
    {

        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function last_document_date()
    {
        try {
            
            $query = "SELECT * FROM $this->table ORDER BY periode DESC LIMIT 1";

            $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);

            $not_exists = count($result) < 1;

            if($this->database->is_error !== null) {

                throw $this->database->is_error;

            } 
            
            else if($not_exists) {

                // Get the current time in epoch time
                $now = time();

                // Subtract 15 days from the current time
                $before = $now - (15 * 60 * 60 * 24);

                // Output the epoch time of 15 days before now
                return $before;

            } 
            
            else {

                return $result[0]['periode'];

            }
        
        }

        catch(PDOException $e) {

            $this->is_success = $e;

        }

    }
}
