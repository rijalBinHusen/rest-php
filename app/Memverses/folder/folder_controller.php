<?php
require_once(__DIR__ . '/folder_model.php');

class Memverses_folder
{
    protected $memverses_folder;
    function __construct()
    {
        $this->memverses_folder = new Memverses_folder_model();
    }

    public function add_folder($id_user, $json_token_id)
    {
        // request
        $req = Flight::request();
        $name = $req->data->name;
        $total_verse_to_show = $req->data->total_verse_to_show;
        $show_next_chapter_on_second = $req->data->show_next_chapter_on_second;
        $read_target = $req->data->read_target;
        $is_show_first_letter = $req->data->is_show_first_letter;
        $is_show_tafseer = $req->data->is_show_tafseer;
        $arabic_size = $req->data->arabic_size;

        $valid_request_body = !is_null($name)
            && is_string($name)
            && !is_null($total_verse_to_show)
            && is_numeric($total_verse_to_show)
            && !is_null($show_next_chapter_on_second)
            && is_numeric($show_next_chapter_on_second)
            && !is_null($read_target)
            && is_numeric($read_target)
            && !is_null($is_show_first_letter)
            && is_bool($is_show_first_letter)
            && !is_null($is_show_tafseer)
            && is_bool($is_show_tafseer)
            && !is_null($arabic_size)
            && is_numeric($arabic_size);

        $result = null;

        if ($valid_request_body) {

            $result = $this->memverses_folder->append_folder($id_user, $name, $total_verse_to_show, $show_next_chapter_on_second, $read_target, $is_show_first_letter, $is_show_tafseer, $arabic_size, $json_token_id);

            if ($this->memverses_folder->is_success !== true) {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $this->memverses_folder->is_success
                    ),
                    500
                );
            } else {

                Flight::json(
                    array(
                        'success' => true,
                        'id' => $result
                    ),
                    201
                );
            }
        } else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add folder, check the data you sent'
                ),
                400
            );
        }
    }
    public function get_folders($id_user, $json_token_id)
    {

        $result = $this->memverses_folder->get_folders($id_user, $json_token_id);

        $is_found = count($result) > 0;
        $is_success = $this->memverses_folder->is_success;

        if ($is_success === true && $is_found) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result,
                    Flight::response()->cache(time() + 30) // cache for 24hours
                ),
                200
            );
        } else if (!$is_found) {
            Flight::json(
                array(
                    "success" => false,
                    "message" => "Folder not found"
                ),
                404
            );
        } else {
            Flight::json(
                array(
                    "success" => false,
                    "message" => $result,
                ),
                500
            );
        }
    }
    public function get_folder_by_id($id_user, $id_folder, $json_token_id)
    {

        $result = $this->memverses_folder->get_folder_by_id($id_user, $id_folder, $json_token_id);

        $is_success = $this->memverses_folder->is_success;
        $is_found = count($result) > 0;

        if ($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        } else if ($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ),
                500
            );
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Folder not found'
                ),
                404
            );
        }
    }

    public function is_folder_changed_by_other_devices($folder_id, $id_user, $json_token_id): bool
    {

        $result = $this->memverses_folder->get_folder_by_id($id_user, $folder_id, $json_token_id);

        $is_changed_by_other_devices = count($result) > 0 && $result['changed_by'] != $json_token_id && $result['changed_by'] != "";
        return $is_changed_by_other_devices;
    }
    /**
     * This function returns an array with 'id' and 'name' keys.
     *
     * @return array {
     *      'id' => string, 
     *      'name' => string,
     *      'total_verse_to_show' => string,
     *      'show_next_chapter_on_second' => string,
     *      'read_target' => string,
     *      'is_show_first_letter' => string,
     *      'is_show_tafseer' => string,
     *      'arabic_size' => string,
     *      'changed_by' => string,
     * }
     * 
     */

    public function get_folder_info_by_id($id_user, $id_folder, $json_token_id): array|false
    {

        $result = $this->memverses_folder->get_folder_by_id($id_user, $id_folder, $json_token_id);

        $is_success = $this->memverses_folder->is_success;
        $is_found = count($result) > 0;

        if ($is_success && $is_found) return $result;
        return false;
    }

    // public function remove_folder($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     $result = $this->memverses_folder->remove_folder($id);

    //     $is_success = $this->memverses_folder->is_success;

    //     if($is_success === true && $result > 0) {
    //         Flight::json(
    //             array(
    //                 'success' => true,
    //                 'message' => 'Delete Folder success',
    //             )
    //         );
    //     }

    //     else if($is_success !== true) {
    //         Flight::json(
    //             array(
    //                 'success' => false,
    //                 'message' => $is_success
    //             ), 500
    //         );
    //         return;
    //     }

    //     else {
    //         Flight::json(
    //             array(
    //                 'success' => false,
    //                 'message' => 'Folder not found'
    //             ), 404
    //         );
    //     }
    // }

    public function update_folder_by_id($id_folder, $id_user, $json_token_id)
    {
        // catch the query string request
        $req = Flight::request();
        $name = $req->data->name;
        $total_verse_to_show = $req->data->total_verse_to_show;
        $show_next_chapter_on_second = $req->data->show_next_chapter_on_second;
        $read_target = $req->data->read_target;
        $is_show_first_letter = $req->data->is_show_first_letter;
        $is_show_tafseer = $req->data->is_show_tafseer;
        $arabic_size = $req->data->arabic_size;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional name
        $valid_name = !is_null($name) && !empty($name) && is_string($name);
        if ($valid_name) $keyValueToUpdate["name"] = $name;

        // conditional $total_verse_to_show
        $valid_total_verse_to_show = !is_null($total_verse_to_show) && !empty($total_verse_to_show) && is_numeric($total_verse_to_show);
        if ($valid_total_verse_to_show) $keyValueToUpdate["total_verse_to_show"] = $total_verse_to_show;

        // conditional $show_next_chapter_on_second
        $valid_show_next_chapter_on_second = !is_null($show_next_chapter_on_second) && !empty($show_next_chapter_on_second) && is_numeric($show_next_chapter_on_second);
        if ($valid_show_next_chapter_on_second) $keyValueToUpdate["show_next_chapter_on_second"] = $show_next_chapter_on_second;

        // conditional $read_target
        $valid_read_target = !is_null($read_target) && !empty($read_target) && is_numeric($read_target);
        if ($valid_read_target) $keyValueToUpdate["read_target"] = $read_target;

        // conditional $is_show_first_letter
        $valid_is_show_first_letter = !is_null($is_show_first_letter) && !empty($is_show_first_letter) && is_bool($is_show_first_letter);
        if ($valid_is_show_first_letter) $keyValueToUpdate["is_show_first_letter"] = $is_show_first_letter;

        // conditional $is_show_tafseer
        $valid_is_show_tafseer = !is_null($is_show_tafseer) && !empty($is_show_tafseer) && is_bool($is_show_tafseer);
        if ($valid_is_show_tafseer) $keyValueToUpdate["is_show_tafseer"] = $is_show_tafseer;

        // conditional $arabic_size
        $valid_arabic_size = !is_null($arabic_size) && !empty($arabic_size) && is_numeric($arabic_size);
        if ($valid_arabic_size) $keyValueToUpdate["arabic_size"] = $arabic_size;

        // conditional $changed_by
        $keyValueToUpdate["changed_by"] = $json_token_id;

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if ($is_oke_to_update) {

            $result = $this->memverses_folder->update_folder_by_id($keyValueToUpdate, $id_user, $id_folder);

            $is_success = $this->memverses_folder->is_success;

            if ($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update folder success',
                    )
                );
            } else if ($is_success !== true) {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ),
                    500
                );
            } else {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'Folder not found'
                    ),
                    404
                );
            }
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update folder, check the data you sent'
                ),
                400
            );
        }
    }
}
