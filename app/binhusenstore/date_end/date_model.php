<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_date_model
{
    protected $database;
    var $table = "binhusenstore_date_end";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_date($title, $date_end)
    {

        $data_to_insert = array(
            'title' => $title,
            'date' => $date_end
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_dates()
    {
        $result  = $this->database->select_from($this->table)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function update_date_by_id($data_to_update, $id)
    {

        $result = $this->database->update($this->table, $data_to_update, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function remove_date_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }
}
