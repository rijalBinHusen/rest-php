<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_base_clock_model
{
    protected $database;
    var $table = "my_report_base_clock";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $connection_db = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
        $connection_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->database = Query_builder::getInstance($connection_db);
      
        $this->summary = SummaryDatabase::getInstance($this->table);
    }

    public function append_base_clock($parent, $shift, $no_do, $reg, $start, $finish, $rehat)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_base_clock(
            $nextId,
            $parent,
            $shift,
            $no_do,
            $reg,
            $start,
            $finish,
            $rehat
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function write_base_clock($id, $parent, $shift, $no_do, $reg, $start, $finish, $rehat)
    {

        $data_to_insert = array(
            "id" => $id,
            'parent' => $parent,
            'shift' => $shift,
            'no_do' => $no_do,
            'reg' => $reg,
            'start' => $start,
            'finish' => $finish,
            'rehat' => $rehat
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function get_base_clock_by_parent($parent)
    {
        $result  = $this->database->select_where($this->table, 'parent', $parent)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        else {

            return $result;

        }
    }

    public function remove_base_clock_by_parent($parent)
    {
        $result = $this->database->delete($this->table, 'parent', $parent);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;

        }

    }

    public function get_base_clock_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_base_clock_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }

    public function remove_base_clock($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
