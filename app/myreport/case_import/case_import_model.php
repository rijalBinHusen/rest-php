<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_case_import_model
{
    protected $database;
    var $table = "my_report_case_import";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
      
        $this->summary = SummaryDatabase::getInstance($this->table);
    }

    public function get_cases_import($limit)
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

    public function append_case_import($bagian, $divisi, $fokus, $kabag, $karu, $keterangan1, $keterangan2, $periode, $temuan)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_case_import(
            $nextId,
            $bagian,
            $divisi,
            $fokus,
            $kabag,
            $karu,
            $keterangan1,
            $keterangan2,
            $periode,
            $temuan
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function get_case_import_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_case_import_by_id(array $data, $where, $id)
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

    public function write_case_import($id, $bagian, $divisi, $fokus, $kabag, $karu, $keterangan1, $keterangan2, $periode, $temuan)
    {

        $data_to_insert = array(
            "id" => $id,
            'bagian' => $bagian,
            'divisi' => $divisi,
            'fokus' => $fokus,
            'kabag' => $kabag,
            'karu' => $karu,
            'keterangan1' => $keterangan1,
            'keterangan2' => $keterangan2,
            'periode' => $periode,
            'temuan' => $temuan,
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function remove_case_import($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
