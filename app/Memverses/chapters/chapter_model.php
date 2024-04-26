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

    public function append_chapter($id_chapter_client, $id_user, $chapter, $verse, $readed_times)
    {

        $data_to_insert = array(
            'id_chapter_client' => $id_chapter_client,
            'id_user' => $id_user,
            'chapter' => $chapter,
            'verse' => $verse,
            'readed_times' => $readed_times
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_chapters($id_user, $id_folder)
    {
        $where_s = array(
            'id_user' => $id_user,
            'id_folder' => $id_folder
        );
        $result = $this->database->select_where_s($this->table, $where_s)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($result) > 0) {

            $convert_data_type_chapters = $this->convert_data_type($result);

            return $convert_data_type_chapters;
        }

        $this->is_success = $this->database->is_error;
    }

    // public function get_chapter_by_id($id_user, $id_chapter)
    // {

    //     $where_s = array(
    //         'id_user' => $id_user,
    //         'id_chapter' => $id_chapter
    //     );

    //     $retrieve_chapter = $this->database->select_where_s($this->table, $where_s)->fetchAll(PDO::FETCH_ASSOC);

    //     if ($this->database->is_error === null && count($retrieve_chapter) > 0) {

    //         $convert_data_type = $this->convert_data_type($retrieve_chapter);

    //         return $convert_data_type[0];
    //     }

    //     $this->is_success = $this->database->is_error;
    //     return array();
    // }

    public function update_chapter_by_id(array $data, $id_user, $id_chapter)
    {

        $where_s = array(
            'id_user' => $id_user,
            'id_chapter' => $id_chapter
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
                "id_chapter" => $chapter_value['id_chapter'],
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
