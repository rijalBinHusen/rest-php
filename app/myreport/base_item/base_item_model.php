<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_base_item_model
{
    protected $database;
    var $table = "my_report_base_item";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = new Query_builder();
        $this->summary = new SummaryDatabase($this->table);
    }

    public function get_base_items()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
        }
        else {
            return $result;
        }
    }

    public function append_base_item($item_kode, $item_name, $last_used)
    {
        $nextId = $this->summary->getNextId();
        // data to write to database
        $res = array(
            "id" => $nextId,
            'item_kode' => $item_kode,
            'item_name' => $item_name,
            'last_used' => $last_used
        );

        $this->database->insert($this->table, $res);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($nextId);
            return $nextId;

        }

    }

    public function get_base_item_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {
            $this->is_success = $this->database->is_error;
            return array();
        } else {
            return $result;
        }

    }

    public function update_base_item_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function write_base_item(array $data)
    {
        $this->database->insert($this->table, $data);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($data['id']);
            return $data['id'];

        }

    }

    public function remove_base_item($id)
    {
        $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return "Delete base item success";

        }

    }
}
