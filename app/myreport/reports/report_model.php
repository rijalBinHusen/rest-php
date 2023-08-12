<?php
require_once(__DIR__ . '/../../../utils/database.php');

class My_report_report_model
{
    protected $database;
    var $is_success = true;

    function __construct()
    {
        $this->database = Query_builder::getInstance();
    }

    public function retrieve_weekly_report($supervisor_id, $head_supervisor_id, $periode1, $periode2)
    {
        $result = array();

        // get documents, my_report_document periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_document = "SELECT * FROM my_report_document WHERE periode BETWEEN $periode1 AND $periode2";
        $query_leader = "";
        if ($head_supervisor_id) {
            
            $query_document = $query_document . " AND head_spv_id = '$head_supervisor_id'";
            $query_leader = " AND head_spv_id = '$head_supervisor_id'";
        } else {
            
            $query_document = $query_document . " AND supervisor_id = '$supervisor_id'";
            $query_leader = " AND supervisor_id = '$supervisor_id'";
        }
        
        $result_documents  = $this->database->sqlQuery($query_document)->fetchAll(PDO::FETCH_ASSOC);
        
        $is_documents_not_exists = count($result_documents) === 0;
        if ($is_documents_not_exists) {
            return $result;
        }

        $grouping_document_with_same_periode = [];

        // Calculate summaries based on the 'oeruide' key
        foreach ($result_documents as $document) {
            $periode = $document['periode'];
            if (!isset($grouping_document_with_same_periode[$periode])) {
                $grouping_document_with_same_periode[$periode] = ['sum' => 0, 'count' => 0];
                // total_do: number,
                // total_kendaraan: number,
                // total_waktu: number,
                // periode: number|string,
                // shift: number,
                // is_generated_document: boolean,
                // item_variance: number,
                // plan_out: number,
                // total_item_keluar: number,
                // total_item_moving: number,
                // total_product_not_FIFO: number,
                // total_qty_in: number,
                // total_qty_out: number,
                // total_komplain_muat: number
            }
            $grouping_document_with_same_periode[$periode]['sum'] += $document['value'];
            $grouping_document_with_same_periode[$periode]['count']++;
        }

        // // Display the grouping_document_with_same_periode
        // print_r($grouping_document_with_same_periode);

        $result = array("problems" => array());
        
        for($index = 0; $index < count($result_documents); $index++) {
            // set komplain count
            $result_documents[$index]['total_komplain_muat'] = 0;
            $periode_document = $result_documents[$index]['periode'];

            // // get komplain, my_report_complain periode between $periode1 and $periode2 by supervisor_id or head_spv_id
            $query_complain = "SELECT * FROM my_report_complain WHERE periode = $periode_document" . $query_leader;
            // retrieve problem, my_report_problem tanggal_mulai $periode1 and $periode2 by supervisor_id or head_spv_id
            $query_problem = "SELECT * FROM my_report_problem WHERE tanggal_mulai = $periode_document" . $query_leader;
            // // retrieve field problem, my_report_field_problem periode between $periode1 and $periode2 by supervisor_id or head_spv_id
            $query_field_problem = "SELECT * FROM my_report_field_problem WHERE periode = $periode_document" . $query_leader;
            // // retrieve case, my_report_cases periode between $periode1 and $periode2 by supervisor_id or head_spv_id
            $query_case = "SELECT * FROM my_report_cases WHERE periode  = $periode_document" . $query_leader;
            
            $result_complains = $this->database->sqlQuery($query_complain)->fetchAll(PDO::FETCH_ASSOC);
            $is_complain_exists = count($result_complains) > 0;
            if($is_complain_exists) {
                
                $complain_key_value_to_push = array();
                
                foreach($result_complains as $complain) {

                    $result_documents[$index]['total_komplain_muat'] += $complain['is_count'];
                    
                    $complain_key_value_to_push['periode'] = $complain['periode'];
                    $complain_key_value_to_push['masalah'] = "[ KOMPLAIN MUAT ] " .$complain['masalah'];
                    $complain_key_value_to_push['sumber_masalah'] = $complain['sumber_masalah'];
                    $complain_key_value_to_push['solusi'] = $complain['solusi'];
                    $complain_key_value_to_push['pic'] = $complain['pic'];
                    $complain_key_value_to_push['dead_line'] = $complain['dl'];
                    
                }


                array_push($result['problems'], $complain_key_value_to_push);
            }

            $result_problems = $this->database->sqlQuery($query_problem)->fetchAll(PDO::FETCH_ASSOC);
            $is_problems_exists = count($result_problems) > 0;
            if($is_problems_exists) {

                $problem_key_value_to_push = array();

                foreach($result_problems as $problem) {

                    $problem_key_value_to_push['periode'] = $problem['tanggal_mulai'];
                    $problem_key_value_to_push['masalah'] = "[ MASALAH DI LAPANGAN ] " .$problem['masalah'];
                    $problem_key_value_to_push['sumber_masalah'] = $problem['sumber_masalah'];
                    $problem_key_value_to_push['solusi'] = $problem['solusi'];
                    $problem_key_value_to_push['pic'] = $problem['pic'];
                    $problem_key_value_to_push['dead_line'] = $problem['dl'];
                    
                }


                array_push($result['problems'], $problem_key_value_to_push);
            }

            $result_field_problems = $this->database->sqlQuery($query_field_problem)->fetchAll(PDO::FETCH_ASSOC);
            $is_field_problems_exists = count($result_field_problems) > 0;
            if($is_field_problems_exists) {

                $field_problem_key_value_to_push = array();

                foreach($result_field_problems as $field_problem) {

                    $field_problem_key_value_to_push['periode'] = $field_problem['periode'];
                    $field_problem_key_value_to_push['masalah'] = "[ KENDALA LAPANGAN ] " .$field_problem['masalah'];
                    $field_problem_key_value_to_push['sumber_masalah'] = $field_problem['sumber_masalah'];
                    $field_problem_key_value_to_push['solusi'] = $field_problem['solusi'];
                    $field_problem_key_value_to_push['pic'] = $field_problem['pic'];
                    $field_problem_key_value_to_push['dead_line'] = $field_problem['dl'];
                    
                }


                array_push($result['problems'], $field_problem_key_value_to_push);
            }

            $result_cases = $this->database->sqlQuery($query_case)->fetchAll(PDO::FETCH_ASSOC);
            $is_cases_exists = count($result_cases) > 0;
            if($is_cases_exists) {

                $case_key_value_to_push = array();

                foreach($result_cases as $case) {

                    $case_key_value_to_push['periode'] = $case['periode'];
                    $case_key_value_to_push['masalah'] = "[ KASUS ] " .$case['masalah'];
                    $case_key_value_to_push['sumber_masalah'] = $case['sumber_masalah'];
                    $case_key_value_to_push['solusi'] = $case['solusi'];
                    $case_key_value_to_push['pic'] = $case['pic'];
                    $case_key_value_to_push['dead_line'] = $case['dl'];
                    
                }


                array_push($result['problems'], $case_key_value_to_push);
            }

        }

        

        $result['daily_reports'] = $result_documents;

        if ($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
        } else {

            return $result;
        }

    }
}
