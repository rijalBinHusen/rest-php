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

    public function append_chapter($id_chapter_client, $id_user, $chapter, $verse, $readed_times, $id_folder, $json_token_id)
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

            $this->update_changed_by_on_folder($json_token_id, $id_user, $id_folder);
            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_chapters($id_user, $id_folder)
    {
        // check is folder updated by other devices? if false return empty array();
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

    public function update_readed_times($id_user, $id_chapter, $json_token_id)
    {
        $chapter = $this->get_chapter_by_id($id_user, $id_chapter);
        $is_found = count($chapter) > 0;
        if (!$is_found) return array();

        $data_to_update = array(
            "readed_times" => $chapter["readed_times"] + 1
        );

        $is_updated = $this->update_chapter_by_id($data_to_update, $id_user, $id_chapter);
        if ($is_updated > 0) $this->update_changed_by_on_folder($json_token_id, $id_user, $chapter["id_folder"]);
        return $is_updated;
    }

    public function move_chapter_to_folder($id_folder_destination, $id_user, $id_chapter, $json_token_id)
    {
        $chapter = $this->get_chapter_by_id($id_user, $id_chapter);
        $is_found = count($chapter) > 0;
        if (!$is_found) return array();

        $data_to_update = array("id_folder" => $id_folder_destination);

        $is_updated = $this->update_chapter_by_id($data_to_update, $id_user, $id_chapter);
        if ($is_updated > 0) {
            $this->update_changed_by_on_folder($json_token_id, $id_user, $chapter["id_folder"]);
            $this->update_changed_by_on_folder($json_token_id, $id_user, $id_folder_destination);
        }
        return $is_updated;
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


    public function get_verses($id_user, $id_folder)
    {
        $db_virtual_table_view = "memverses_unreaded_verses";

        $where_s = array('id_user' => $id_user, 'id_folder' => $id_folder);

        $result = $this->database->select_where_s($db_virtual_table_view, $where_s, "", false)->fetchAll(PDO::FETCH_ASSOC);

        $is_no_error = $this->database->is_error === null;
        $is_found = count($result) > 0;
        if ($is_no_error && $is_found) {

            $convert_data_type_chapters = $this->convert_data_type($result);
            // update folder changed
            return $convert_data_type_chapters;
        }

        $this->is_success = $this->database->is_error;
    }

    public function reset_readed_times($json_token_id, $id_user, $id_folder)
    {
        $data_to_update = array('readed_times' => '0');
        $this->database->update($this->table, $data_to_update, 'id_folder', $id_folder);
        $this->update_changed_by_on_folder($json_token_id, $id_user, $id_folder);
    }

    private function update_changed_by_on_folder($json_token_id, $id_user, $id_folder)
    {
        $data_to_update = array("changed_by" => $json_token_id);
        $folder_operation = new Memverses_folder_model();
        $folder_operation->update_folder_by_id($data_to_update, $id_user, $id_folder);
    }

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
