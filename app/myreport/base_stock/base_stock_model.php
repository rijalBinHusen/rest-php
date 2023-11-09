<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/summary_db.php');

class My_report_base_stock_model
{
    protected $database;
    var $table = "my_report_base_stock";
    var $is_success = true;
    private $summary = null;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
      
        $this->summary = SummaryDatabase::getInstance($this->table);
    }

    public function append_base_stock($parent, $shift, $item, $awal, $in_stock, $out_stock, $date_in, $plan_out, $date_out, $date_end, $real_stock, $problem)
    {
        $nextId = $this->summary->getNextId();
        // write to database
        $this->write_base_stock(
            $nextId,
            $parent,
            $shift,
            $item,
            $awal,
            $in_stock,
            $out_stock,
            $date_in,
            $plan_out,
            $date_out,
            $date_end,
            $real_stock,
            $problem
        );

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $nextId;

        }

    }

    public function write_base_stock($id, $parent, $shift, $item, $awal, $in_stock, $out_stock, $date_in, $plan_out, $date_out, $date_end, $real_stock, $problem)
    {

        $data_to_insert = array(
            "id" => $id,
            'parent' => $parent,
            'shift' => $shift,
            'item' => $item,
            'awal' => $awal,
            'in_stock' => $in_stock,
            'out_stock' => $out_stock,
            'date_in' => $date_in,
            'plan_out' => $plan_out,
            'date_out' => $date_out,
            'date_end' => $date_end,
            'real_stock' => $real_stock,
            'problem' => $problem
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            $this->summary->updateLastId($id);
            return $id;

        }

    }

    public function get_base_stock_by_parent($parent)
    {
        $result  = $this->database->select_where($this->table, 'parent', $parent)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            
        }
        else {

            return $result;

        }
    }

    public function remove_base_stock_by_parent($parent)
    {
        $result = $this->database->delete($this->table, 'parent', $parent);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return 0;

        } else {

            return $result;

        }

    }

    public function get_base_stock_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
            return array();

        } else {

            return $result;
        }

    }

    public function update_base_stock_by_id(array $data, $where, $id)
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

    public function remove_base_stock($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;

        } else {

            return $result;

        }

    }
}
