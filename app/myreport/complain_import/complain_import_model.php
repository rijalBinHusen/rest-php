<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_complain_import_model
{
    protected $database;
    var $table = "my_report_complain_import";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
      
        $this->summary = SummaryDatabase::getInstance($this->table);
    }

    public function get_complains_import($limit)
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

    public function append_complain_import($customer, $do_, $gudang, $item, $kabag, $nomor_SJ, $nopol, $real_, $row_, $spv, $tally, $tanggal_bongkar, $tanggal_info, $tanggal_komplain, $tanggal_SJ, $type, $is_inserted)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_complain_import(
            $nextId,
            $customer,
            $do_,
            $gudang,
            $item,
            $kabag,
            $nomor_SJ,
            $nopol,
            $real_,
            $row_,
            $spv,
            $tally,
            $tanggal_bongkar,
            $tanggal_info,
            $tanggal_komplain,
            $tanggal_SJ,
            $type,
            $is_inserted
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function get_complain_import_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_complain_import_by_id(array $data, $where, $id)
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

    public function write_complain_import($id, $customer, $do_, $gudang, $item, $kabag, $nomor_SJ, $nopol, $real_, $row_, $spv, $tally, $tanggal_bongkar, $tanggal_info, $tanggal_komplain, $tanggal_SJ, $type, $is_inserted)
    {

        $data_to_insert = array(
            "id" => $id,
            'customer' => $customer,
            'do_' => $do_,
            'gudang' => $gudang,
            'item' => $item,
            'kabag' => $kabag,
            'nomor_SJ' => $nomor_SJ,
            'nopol' => $nopol,
            'real_' => $real_,
            'row_' => $row_,
            'spv' => $spv,
            'tally' => $tally,
            'tanggal_bongkar' => $tanggal_bongkar,
            'tanggal_info' => $tanggal_info,
            'tanggal_komplain' => $tanggal_komplain,
            'tanggal_SJ' => $tanggal_SJ,
            'type_' => $type,
            'is_inserted' => (int)$is_inserted,
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function remove_complain_import($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
