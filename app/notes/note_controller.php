<?php
require_once(__DIR__ . '/note_model.php.php');

class note_app
{
    protected $note_app;
    function __construct()
    {
        $this->note_app = new Note_app_model();
    }
    
    public function add_note()
    {
        // request
        $req = Flight::request();
        $tanggal = $req->data->tanggal;
        $isi = $req->data->isi;

        $result = null;

        $is_request_body_not_oke = is_null($tanggal) && is_null($isi);

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add note, check the data you sent'
                ), 400
            );
            return;
        }

        $result = $this->note_app->append_note($tanggal, $isi);

        if($this->note_app->is_success === true) {
        
            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ), 201
            );
        } 
        
        else {
            
            Flight::json(
                array(
                    'success'=> false,
                    'message'=> $this->note_app->is_success
                ), 500
            );
        }
    }
    
    public function get_notes()
    {
        // catch the query string request
        $req = Flight::request();
        $key_word = $req->data->search;

        if(!is_null($key_word)) {

            $result = $this->note_app->get_notes_by_key_word($key_word);
        } else {

            $result = $this->note_app->get_notes();
        }
                
        $is_exists = count($result) > 0;

        if($this->note_app->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->note_app->is_success !== true) {

            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {

            Flight::json( array(
            "success" => false,
            "message" => "note not found"
            ), 404);
        }

    }
    
    public function get_note_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->note_app->get_note_by_id($id);

        $is_success = $this->note_app->is_success;

        $is_found = count($result) > 0;

        if($is_success === true && $is_found) {

            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
                )
            );
        }

        else if($is_success !== true) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'note not found'
                ), 404
            );
        }
    }

    public function remove_note($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->note_app->remove_note_by_id($id);

        $is_success = $this->note_app->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete note success',
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'note not found'
                ), 404
            );
        }
    }

    public function update_note_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $tanggal = $req->data->tanggal;
        $isi = $req->data->isi;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $tanggal
        $valid_tanggal = !is_null($tanggal);
        if ($valid_tanggal) {
            $keyValueToUpdate["tanggal"] = $tanggal;
        }

        // conditional $isi
        $valid_isi = !is_null($isi);
        if ($valid_isi) {
            $keyValueToUpdate["isi"] = $isi;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->note_app->update_note_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->note_app->is_success;
    
            if($is_success === true && $result > 0) {

                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update note success',
                    )
                );
            }
    
            else if($is_success !== true) {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ), 500
                );
            }
    
            else {

                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'Note not found'
                    ), 404
                );
            }
        } 
        
        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update note, check the data you sent'
                )
            );
        }
    }
}
