<?php
require_once(__DIR__ . '/../../utils/database.php');

class Note_app_model
{
    protected $database;
    var $table_name = "note_app";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_note($owner_id, $isi)
    {

        $data_to_insert = array(
            'owner_id' => $owner_id,
            'isi' => $isi
        );

        $inserted_id = $this->database->insert($this->table_name, $data_to_insert);

        if($this->database->is_error === null) {
    
            return $inserted_id;
        }   

        $this->is_success = $this->database->is_error;

    }

    public function get_notes()
    {
        $query = "SELECT * FROM $this->table_name ORDER BY id DESC LIMIT 30";
        $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_notes_by_key_word($key_word)
    {
        $query = "SELECT * FROM $this->table_name WHERE MATCH(isi) AGAINST ('$key_word' IN NATURAL LANGUAGE MODE) ORDER BY id DESC";
        $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {
            
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_note_by_id($id)
    {

        $result = $this->database->select_where($this->table_name, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result;
        }
        
        $this->is_success = $this->database->is_error;
        return array();
        
    }

    public function update_note_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table_name, $data, $where, $id);

        if($this->database->is_error === null) {
    
            if($result === 0) {

                $query = "SELECT EXISTS(SELECT id FROM $this->table_name WHERE id = '$id')";
                return $this->database->sqlQuery($query)->fetchColumn();
            }
            
            return $result;
        } 

        $this->is_success = $this->database->is_error;

    }

    public function remove_note_by_id($id)
    {
        $result = $this->database->delete($this->table_name, 'id', $id);

        if($this->database->is_error === null) {
    
            return $result;
        }
        
        $this->is_success = $this->database->is_error;

    }
}
