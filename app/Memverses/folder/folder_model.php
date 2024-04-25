<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Memverses_folder_model
{
    protected $database;
    var $table = "memverses_folder";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_folder($id_folder, $id_user, $name, $total_verse_to_show, $next_chapter_on_second, $read_target, $is_show_first_letter, $is_show_tafseer, $arabic_size, $changed_by)
    {

        $data_to_insert = array(
            'id_folder' => $id_folder,
            'id_user' => $id_user,
            'name' => $name,
            'total_verse_to_show' => $total_verse_to_show,
            'next_chapter_on_second' => $next_chapter_on_second,
            'read_target' => $read_target,
            'show_first_letter' => $is_show_first_letter,
            'show_tafseer' => boolval($is_show_tafseer),
            'arabic_size' => boolval($arabic_size),
            'changed_by' => $changed_by,
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_folders($id_user)
    {

        $result = $this->database->select_where($this->table, 'id_user', $id_user)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($result) > 0) {

            $convert_data_type_folders = $this->convert_data_type($result);

            return $convert_data_type_folders;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_folder_by_id($id_user, $id_folder)
    {

        $where_s = array(
            'id_user' => $id_user,
            'id_folder' => $id_folder
        );

        $retrieve_folder = $this->database->select_where_s($this->table, $where_s)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($retrieve_folder) > 0) {

            $convert_data_type = $this->convert_data_type($retrieve_folder);

            return $convert_data_type[0];
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_folder_by_id(array $data, $id_user, $id_folder)
    {

        $where_s = array(
            'id_user' => $id_user,
            'id_folder' => $id_folder
        );

        $result = $this->database->update_where_s($this->table, $data, $where_s);

        if ($this->database->is_error === null) return $result;


        $this->is_success = $this->database->is_error;
    }

    // public function remove_folder_by_id($id)
    // {
    //     $result = $this->database->delete($this->table, 'id', $id);

    //     if ($this->database->is_error === null) {

    //         return $result;
    //     }

    //     $this->is_success = $this->database->is_error;
    // }

    private function convert_data_type($folders)
    {

        $result = array();
        // mapping folders
        foreach ($folders as $folder_value) {

            $array_to_push = array(
                "id" => $folder_value['id'],
                "id_folder" => $folder_value['id_folder'],
                "name" => $folder_value['name'],
                "verse_to_show" => (int)$folder_value['verse_to_show'],
                "next_chapter_on_second" => (int)$folder_value['next_chapter_on_second'],
                "read_target" => (int)$folder_value['read_target'],
                "is_show_first_letter" => boolval($folder_value['is_show_first_letter']),
                "is_show_tafseer" => boolval($folder_value['is_show_tafseer']),
                "arabic_size" => (int)$folder_value['arabic_size'],
                "last_changed" => $folder_value['last_changed'],
            );

            array_push($result, $array_to_push);
        }

        return $result;
    }
}
