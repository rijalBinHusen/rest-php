<?php
require_once(__DIR__ . '/chapter_model.php');

class Memverses_chapter
{
    protected $memverses_chapter;
    function __construct()
    {
        $this->memverses_chapter = new Memverses_chapter_model();
    }

    public function add_chapter($id_user, $json_token_id)
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

            $result = $this->memverses_chapter->append_chapter($id_chapter_client, $id_user, $chapter, $verse, $readed_times, $id_folder, $json_token_id);

            if ($result) {

                Flight::json(
                    array(
                        'success' => true,
                        'id' => $result
                    ),
                    201
                );
            } else {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $this->memverses_chapter->is_success
                    ),
                    500
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

    public function add_chapter_and_verses($id_user, $json_token_id)
    {
        // request
        $req = Flight::request();
        $id_folder = $req->data->id_folder;
        $chapter = $req->data->chapter;
        $verse_start = $req->data->verse_start;
        $verse_end = $req->data->verse_end;

        $whats_to_check = array(
            "id_folder" => "string",
            "chapter" => "number",
            "verse_start" => "number",
            "verse_end" => "number",
        );

        $validator = new Validator();
        $is_valid_request_body = $validator->check_type($req->data, $whats_to_check);
        // verse_start should > 0;
        if ($verse_start < 0) $is_valid_request_body = false;
        // verse_start should be < verse_end
        if ($verse_start > $verse_end) $is_valid_request_body = false;
        // end start should <= 286;
        if ($verse_end > 286) $is_valid_request_body = false;

        $result = null;

        if ($is_valid_request_body) {

            $result = $this->memverses_chapter->append_chapter_and_verses($id_user, $chapter, $verse_start, $verse_end, $id_folder, $json_token_id);

            if ($result) {

                Flight::json(
                    array(
                        'success' => true,
                        'message' => "Chapter and verses added"
                    ),
                    201
                );
            } else {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $this->memverses_chapter->is_success
                    ),
                    500
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

        $result = $this->memverses_chapter->get_verses($id_user, $id_folder, $json_token_id);

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

    public function update_folder($id_chapter, $id_user, $json_token_id)
    {
        // catch the query string request
        $req = Flight::request();
        $id_folder = $req->data->id_folder;

        // conditional id_folder
        $valid_id_folder = !is_null($id_folder) && !empty($id_folder) && is_string($id_folder);
        if ($valid_id_folder) $keyValueToUpdate["id_folder"] = $id_folder;

        if ($valid_id_folder) {

            $result = $this->memverses_chapter->move_chapter_to_folder($id_folder, $id_user, $id_chapter, $json_token_id);

            $is_success = $this->memverses_chapter->is_success;

            if ($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Chapter moved',
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

    public function update_readed($id_chapter, $id_user, $json_token_id)
    {

        $result = $this->memverses_chapter->update_readed_times($id_user, $id_chapter, $json_token_id);

        $is_success = $this->memverses_chapter->is_success;

        if ($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Readed times updated',
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

    public function reset_readed($id_folder, $id_user, $json_token_id)
    {
        // conditional id_folder
        $in_valid_id_folder = is_null($id_folder) || empty($id_folder) || !is_string($id_folder);
        if ($in_valid_id_folder) {
            Flight::json(
                array(
                    "success" => false,
                    "data" => "Request invalid, check the data you sent"
                ),
                400
            );
            return;
        }

        $result = $this->memverses_chapter->reset_readed_times($json_token_id, $id_user, $id_folder);

        $is_success = $this->memverses_chapter->is_success;

        if ($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Read times reset',
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
                    'message' => 'Chapters not found'
                ),
                404
            );
        }
    }

    // public function get_unreaded_verses($id_user, $id_folder, $json_token_id)
    // {

    //     // check is id user id folder and jti is valid?
    //     $is_parameter_not_oke = !is_string($id_user) || is_null($id_user) || empty($id_user) ||
    //         !is_string($id_folder) || is_null($id_folder) || empty($id_folder) ||
    //         !is_string($json_token_id) || is_null($json_token_id) || empty($json_token_id);

    //     if ($is_parameter_not_oke) {
    //         Flight::json(
    //             array(
    //                 "success" => false,
    //                 "data" => "Request invalid, check the data you sent"
    //             ),
    //             400
    //         );
    //         return;
    //     }

    //     $result = $this->memverses_chapter->get_verses($id_user, $id_folder, $json_token_id);

    //     $is_found = count($result) > 0;
    //     $is_success = $this->memverses_chapter->is_success;

    //     if ($is_success === true && $is_found) {
    //         Flight::json(
    //             array(
    //                 "success" => true,
    //                 "data" => $result
    //             ),
    //             200
    //         );
    //     } else if ($is_success !== true) {
    //         Flight::json(
    //             array(
    //                 "success" => false,
    //                 "message" => $result
    //             ),
    //             500
    //         );
    //     } else {
    //         Flight::json(
    //             array(
    //                 "success" => false,
    //                 "message" => "chapter not found"
    //             ),
    //             404
    //         );
    //     }
    // }
}
