<?php
require_once(__DIR__ . '/report_model.php');

class My_report_report
{
    protected $my_report_report;
    function __construct()
    {
        $this->my_report_report = new My_report_report_model();
    }
    public function get_weekly_report()
    { 
        $supervisor_id = Flight::request()->query->supervisor_id;
        $head_supervisor_id = Flight::request()->query->head_supervisor_id;
        $periode1 = Flight::request()->query->periode1;
        $periode2 = Flight::request()->query->periode2;

        $is_id_valid = !is_null($supervisor_id) || !is_null($head_supervisor_id);
        $is_periode_valid = !is_null($periode1) && is_numeric($periode1) && !is_null($periode2) && is_numeric($periode2);

        // periode1 & 2 mustbe unix epoch time

        $is_query_request_valid = $is_id_valid && $is_periode_valid;
        

        if($is_query_request_valid) {
            $result = $this->my_report_report->retrieve_weekly_report($supervisor_id, $head_supervisor_id, $periode1, $periode2);
            
            $is_found = count($result) > 0;

            $is_success = $this->my_report_report->is_success;
            
            if($is_success === true && $is_found) {
                Flight::json(
                    array(
                        "success" => true,
                        "data" => $result
                        )
                , 200);
            }
            
            else if($is_success !== true) {
                Flight::json( array(
                    "success" => false,
                    "message" => $result
                    )
                , 500);
            }
            
            else {
                Flight::json(array(
                    "success" => false,
                    "message" => "Report not found"
                    )
                , 404);
            }
        }
        
        else {
            Flight::json(array(
                "success" => false,
                "message" => "Invalid query parameter $supervisor_id"
                )
            , 400);
        }

    }
}
