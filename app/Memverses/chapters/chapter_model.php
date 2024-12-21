<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Memverses_chapter_model
{
    protected $database;
    var $table = "memverses_chapters";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_chapter($id_chapter_client, $id_user, $chapter, $verse, $readed_times, $id_folder)
    {

        $data_to_insert = array(
            'id_chapter_client' => $id_chapter_client,
            'id_user' => $id_user,
            'chapter' => $chapter,
            'verse' => $verse,
            'readed_times' => $readed_times,
            'id_folder' => $id_folder
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_chapters($id_user, $id_folder, $json_token_id)
    {
        // check is folder updated by other devices? if false return empty array();
        $folder_operation = new Memverses_folder();
        $is_folder_changed = $folder_operation->is_folder_changed_by_other_devices($id_folder, $id_user, $json_token_id);
        if ($is_folder_changed  == false) return array();

        $where_s = array(
            'id_user' => $id_user,
            'id_folder' => $id_folder
        );

        $result = $this->database->select_where_s($this->table, $where_s)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($result) > 0) {

            $convert_data_type_chapters = $this->convert_data_type($result);
            // update folder changed
            return $convert_data_type_chapters;
        } else if (count($result) === 0) return array();

        $this->is_success = $this->database->is_error;
    }

    public function get_chapter_by_id($id_user, $id_chapter)
    {

        $where_s = array(
            'id_user' => $id_user,
            'id' => $id_chapter
        );

        $retrieve_chapter = $this->database->select_where_s($this->table, $where_s)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($retrieve_chapter) > 0) {

            $convert_data_type = $this->convert_data_type($retrieve_chapter);

            return $convert_data_type[0];
        } else if (count($retrieve_chapter) === 0) return array();

        $this->is_success = $this->database->is_error;
    }

    public function update_chapter_by_id(array $data, $id_user, $id_chapter)
    {

        $where_s = array(
            'id_user' => $id_user,
            'id' => $id_chapter
        );

        $result = $this->database->update_where_s($this->table, $data, $where_s);

        if ($this->database->is_error === null) return $result;


        $this->is_success = $this->database->is_error;
    }

    // public function remove_chapter_by_id($id)
    // {
    //     $result = $this->database->delete($this->table, 'id', $id);

    //     if ($this->database->is_error === null) {

    //         return $result;
    //     }

    //     $this->is_success = $this->database->is_error;
    // }

    private function convert_data_type($chapters)
    {

        $result = array();
        // mapping chapters
        foreach ($chapters as $chapter_value) {

            $array_to_push = array(
                "id" => $chapter_value['id'],
                "id_chapter_client" => $chapter_value['id_chapter_client'],
                "id_folder" => $chapter_value['id_folder'],
                "chapter" => (int)$chapter_value['chapter'],
                "verse" => (int)$chapter_value['verse'],
                "readed_times" => (int)$chapter_value['readed_times']
            );

            array_push($result, $array_to_push);
        }

        return $result;
    }
}
