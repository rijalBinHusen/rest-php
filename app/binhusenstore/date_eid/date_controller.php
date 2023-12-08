<?php
require_once(__DIR__ . '/date_model.php');

class Binhusenstore_date
{
    protected $Binhusenstore_date;
    function __construct()
    {
        $this->Binhusenstore_date = new Binhusenstore_date_model();
    }
    
    public function add_date()
    {
        // request
        $req = Flight::request();
        $year = $req->data->year;
        $date = $req->data->date;

        $result = null;

        $is_request_body_not_oke = is_null($year) || is_null($date);

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add date, check the data you sent'
                ), 400
            );
            return;
        }

        $result = $this->Binhusenstore_date->append_date($year, $date);

        if($this->Binhusenstore_date->is_success === true) {
        
            Flight::json(
                array(
                    'success' => true,
                    'year' => $result
                ), 201
            );
        } 
        
        else {
            
            Flight::json(
                array(
                    'success'=> false,
                    'message'=> $this->Binhusenstore_date->is_success
                ), 500
            );
        }
    }
    
    public function get_dates()
    {

        $result = $this->Binhusenstore_date->get_dates();
                
        $is_exists = count($result) > 0;

        if($this->Binhusenstore_date->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->Binhusenstore_date->is_success !== true) {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {
            Flight::json( array(
            "success" => false,
            "message" => "date not found"
            ), 404);
        }

    }
    public function remove_date($year) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_date->remove_date_by_year($year);

        $is_success = $this->Binhusenstore_date->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete date success',
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
                    'message' => 'date not found'
                ), 404
            );
        }
    }

    public function update_date($year)
    {
        // catch the query string request
        $req = Flight::request();
        $date = $req->data->date;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $date
        $valid_name_date = !is_null($date) && is_string($date) && !empty($date);
        if ($valid_name_date) {
            $keyValueToUpdate["date"] = $date;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->Binhusenstore_date->update_date_by_year($keyValueToUpdate, $year);
    
            $is_success = $this->Binhusenstore_date->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update date success',
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
                        'message' => 'date not found'
                    ), 404
                );
            }
        } 
        
        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update date, check the data you sent'
                )
            );
        }
    }
}
