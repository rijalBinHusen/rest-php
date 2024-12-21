<?php
require_once(__DIR__ . '/chapter_model.php');

class Memverses_chapter
{
    protected $memverses_chapter;
    function __construct()
    {
        $this->memverses_chapter = new Memverses_chapter_model();
    }

    public function add_chapter($id_user)
    {
        // request
        $req = Flight::request();
        $id_folder = $req->data->id_folder;
        $id_chapter_client = $req->data->id_chapter_client;
        $chapter = $req->data->chapter;
        $verse = $req->data->verse;
        $readed_times = $req->data->readed_times;

        $valid_request_body = !is_null($id_folder)
            && is_string($id_folder)
            && !is_null($id_chapter_client)
            && is_numeric($id_chapter_client)
            && !is_null($chapter)
            && is_numeric($chapter)
            && !is_null($verse)
            && is_numeric($verse)
            && !is_null($readed_times)
            && is_numeric($readed_times);

        $result = null;

        if ($valid_request_body) {

            $result = $this->memverses_chapter->append_chapter($id_chapter_client, $id_user, $chapter, $verse, $readed_times, $id_folder);

            if ($this->memverses_chapter->is_success !== true) {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $this->memverses_chapter->is_success
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
                    'message' => 'Failed to add chapter, check the data you sent'
                ),
                400
            );
        }
    }
    public function get_chapters($id_user, $id_folder, $json_token_id)
    {

        $result = $this->memverses_chapter->get_chapters($id_user, $id_folder, $json_token_id);

        $is_found = count($result) > 0;
        $is_success = $this->memverses_chapter->is_success;

        if ($is_success === true && $is_found) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                ),
                200
            );
        } else if ($is_success !== true) {
            Flight::json(
                array(
                    "success" => false,
                    "message" => $result
                ),
                500
            );
        } else {
            Flight::json(
                array(
                    "success" => false,
                    "message" => "chapter not found"
                ),
                404
            );
        }
    }
    public function get_chapter_by_id($id_user, $id_chapter)
    {

        $result = $this->memverses_chapter->get_chapter_by_id($id_user, $id_chapter);

        $is_success = $this->memverses_chapter->is_success;
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
                    'message' => 'Chapter not found'
                ),
                404
            );
        }
    }

    // public function remove_chapter($id) {
    //     // myguest/8
    //     // the 8 will automatically becoming parameter $id
    //     $result = $this->memverses_chapter->remove_chapter($id);

    //     $is_success = $this->memverses_chapter->is_success;

    //     if($is_success === true && $result > 0) {
    //         Flight::json(
    //             array(
    //                 'success' => true,
    //                 'message' => 'Delete chapter success',
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
    //                 'message' => 'chapter not found'
    //             ), 404
    //         );
    //     }
    // }

    public function update_chapter_by_id($id_chapter, $id_user)
    {
        // catch the query string request
        $req = Flight::request();
        $id_folder = $req->data->id_folder;
        $readed_times = $req->data->readed_times;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional id_folder
        $valid_id_folder = !is_null($id_folder) && !empty($id_folder) && is_string($id_folder);
        if ($valid_id_folder) $keyValueToUpdate["id_folder"] = $id_folder;

        // conditional $readed_times
        $valid_readed_times = !is_null($readed_times) && !empty($readed_times) && is_numeric($readed_times);
        if ($valid_readed_times) $keyValueToUpdate["readed_times"] = $readed_times;

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if ($is_oke_to_update) {

            $result = $this->memverses_chapter->update_chapter_by_id($keyValueToUpdate, $id_user, $id_chapter);

            $is_success = $this->memverses_chapter->is_success;

            if ($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update chapter success',
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
                        'message' => 'Chapter not found'
                    ),
                    404
                );
            }
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update chapter, check the data you sent'
                ),
                400
            );
        }
    }
}
