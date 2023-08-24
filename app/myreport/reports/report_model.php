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

        $result = array("problems" => array());
        // Calculate summaries based on the 'oeruide' key
        foreach ($result_documents as $document) {
            $periode = $document['periode'];

            if (isset($grouping_document_with_same_periode[$periode])) {
                // increment document report
                $grouping_document_with_same_periode[$periode]['total_do'] += $document['total_do'];
                $grouping_document_with_same_periode[$periode]['total_kendaraan'] += $document['total_kendaraan'];
                $grouping_document_with_same_periode[$periode]['total_waktu'] += $document['total_waktu'];
                $grouping_document_with_same_periode[$periode]['item_variance'] += $document['item_variance'];
                $grouping_document_with_same_periode[$periode]['plan_out'] += $document['plan_out'];
                $grouping_document_with_same_periode[$periode]['total_item_keluar'] += $document['total_item_keluar'];
                $grouping_document_with_same_periode[$periode]['total_item_moving'] += $document['total_item_moving'];
                $grouping_document_with_same_periode[$periode]['total_product_not_FIFO'] += $document['total_product_not_FIFO'];
                $grouping_document_with_same_periode[$periode]['total_qty_in'] += $document['total_qty_in'];
                $grouping_document_with_same_periode[$periode]['total_qty_out'] += $document['total_qty_out'];
                continue;
            }

            // document report
            $grouping_document_with_same_periode[$periode] = array (
                    'periode' => $document['periode'],
                    'shift' => $document['shift'],
                    'total_do' => $document['total_do'],
                    'total_kendaraan' => $document['total_kendaraan'],
                    'total_waktu' => $document['total_waktu'],
                    'item_variance' => $document['item_variance'],
                    'plan_out' => $document['plan_out'],
                    'total_item_keluar' => $document['total_item_keluar'],
                    'total_item_moving' => $document['total_item_moving'],
                    'total_product_not_FIFO' => $document['total_product_not_FIFO'],
                    'total_qty_in' => $document['total_qty_in'],
                    'total_qty_out' => $document['total_qty_out'],
                    'warehouse_id' => $document['warehouse_id'],
                    'total_komplain_muat' => 0
                );
        }
        
        // problems
        // // get komplain, my_report_complain periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $complain_column_to_select = "my_report_complain.is_count, my_report_complain.periode, my_report_complain.masalah, my_report_complain.sumber_masalah, my_report_complain.solusi, my_report_complain.pic, my_report_complain.dl, my_report_supervisor.supervisor_name";
        $complain_inner_join = "INNER JOIN my_report_supervisor ON (my_report_complain.supervisor_id = my_report_supervisor.id)";
        $query_complain = "SELECT $complain_column_to_select FROM `my_report_complain` $complain_inner_join  WHERE periode BETWEEN $periode1 AND $periode2" . $query_leader;
        // $query_complain = "SELECT * FROM my_report_complain WHERE periode BETWEEN $periode1 AND $periode2" . $query_leader;
       
        $result_complains = $this->database->sqlQuery($query_complain)->fetchAll(PDO::FETCH_ASSOC);
        $is_complain_exists = count($result_complains) > 0;
        if($is_complain_exists) {
            
            foreach($result_complains as $complain) {

                $grouping_document_with_same_periode[$periode]['total_komplain_muat'] += $complain['is_count'];

                $complain_to_push = array(

                    'periode' => $complain['periode'],
                    'masalah' => $complain['masalah'] .' Karu ' .$complain['supervisor_name'],
                    'sumber_masalah' => $complain['sumber_masalah'],
                    'solusi' => $complain['solusi'],
                    'pic' => $complain['pic'],
                    'dead_line' => $complain['dl'],
                );
                
                
                array_push($result['problems'], $complain_to_push);
            }
        }

        // ==================================================================================

        // retrieve problem, my_report_problem tanggal_mulai $periode1 and $periode2 by supervisor_id or head_spv_id
        $problem_column_to_select = "my_report_base_item.item_name, my_report_problem.tanggal_mulai, my_report_problem.masalah, my_report_problem.sumber_masalah, my_report_problem.solusi, my_report_problem.pic, my_report_problem.dl, my_report_supervisor.supervisor_name";
        $problem_inner_join = "INNER JOIN my_report_supervisor ON (my_report_problem.supervisor_id = my_report_supervisor.id) INNER JOIN my_report_base_item ON (my_report_problem.item_kode = my_report_base_item.item_kode)";
        $query_problem = "SELECT $problem_column_to_select FROM `my_report_problem` $problem_inner_join  WHERE tanggal_mulai BETWEEN $periode1 AND $periode2" . $query_leader;

        $result_problems = $this->database->sqlQuery($query_problem)->fetchAll(PDO::FETCH_ASSOC);
        $is_problems_exists = count($result_problems) > 0;
        if($is_problems_exists) {

            foreach($result_problems as $problem) {

                $problem_to_push = array(

                    'periode' => $problem['tanggal_mulai'],
                    'masalah' => "[ MASALAH DI LAPANGAN ] " .$problem['item_name'] ." " .$problem['masalah'] . " Karu " .$problem['supervisor_name'],
                    'sumber_masalah' => $problem['sumber_masalah'],
                    'solusi' => $problem['solusi'],
                    'pic' => $problem['pic'],
                    'dead_line' => $problem['dl']
                );
                
                array_push($result['problems'], $problem_to_push);
            }
        }

        // ==================================================================================

        // // retrieve field problem, my_report_field_problem periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_field_problem = "SELECT * FROM my_report_field_problem WHERE periode BETWEEN $periode1 AND $periode2" . $query_leader;

        $result_field_problems = $this->database->sqlQuery($query_field_problem)->fetchAll(PDO::FETCH_ASSOC);
        $is_field_problems_exists = count($result_field_problems) > 0;
        if($is_field_problems_exists) {

            foreach($result_field_problems as $field_problem) {

                $field_problem_to_push = array (

                    'periode' => $field_problem['periode'],
                    'masalah' => "[ KENDALA LAPANGAN ] " .$field_problem['masalah'],
                    'sumber_masalah' => $field_problem['sumber_masalah'],
                    'solusi' => $field_problem['solusi'],
                    'pic' => $field_problem['pic'],
                    'dead_line' => $field_problem['dl'],
                );
                
                array_push($result['problems'], $field_problem_to_push);
            }
        }

        // ==================================================================================
        
        // // retrieve case, my_report_cases periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_case = "SELECT * FROM my_report_cases WHERE periode BETWEEN $periode1 AND $periode2" . $query_leader;

        $result_cases = $this->database->sqlQuery($query_case)->fetchAll(PDO::FETCH_ASSOC);
        $is_cases_exists = count($result_cases) > 0;
        if($is_cases_exists) {

            foreach($result_cases as $case) {

                $case_to_push = array(

                    'periode' => $case['periode'],
                    'masalah' => "[ KASUS ] " .$case['masalah'],
                    'sumber_masalah' => $case['sumber_masalah'],
                    'solusi' => $case['solusi'],
                    'pic' => $case['pic'],
                    'dead_line' => $case['dl'],
                );

                array_push($result['problems'], $case_to_push);
            }
        }

        $divisions = array();
        $result['daily_reports'] = array();
        
        foreach ($grouping_document_with_same_periode as $key => $value) {

            array_push($result['daily_reports'], $value);

            // find warehouse name
            if(!isset($divisions[$value['warehouse_id']])) {

                $warehouse_id = $value['warehouse_id'];
                // $warehouse_names  = $this->database->select_where('my_report_warehouse', 'id', 'WHS22050001')->fetchAll(PDO::FETCH_ASSOC);
                $warehouse_query = "SELECT * from my_report_warehouse WHERE id = '$warehouse_id'";
                $warehouse_names = $this->database->sqlQuery($warehouse_query)->fetchAll(PDO::FETCH_ASSOC);
                if(count($warehouse_names) > 0) {
                    $divisions[$value['warehouse_id']] = $warehouse_names[0]['warehouse_name'];
                }
            }
        }

        // report info
        // fungsi level name name
        // $PIC_query = "SELECT supervisor_name from my_report_supervisor WHERE id = '$supervisor_id'";
        // if($head_supervisor_id) {
            
        //     $PIC_query = "SELECT head_name from my_report_head_spv WHERE id = '$head_supervisor_id'";
        // }
        // $result_PIC = $this->database->sqlQuery($PIC_query)->fetchAll(PDO::FETCH_ASSOC);
        // $PIC_name = $result_PIC[0]['supervisor_name'];

        // // periode start until end
        // $periode_info = date('Y-m-d', $periode1) ." sampai dengan" .date('Y-m-d', $periode2);

        // // division
        // $division = "";
        // foreach ($divisions as $key => $value) {
        //     $division = $division .$value ." | ";
        // }

        // $result['info'] = array(
        //     'PIC_name' => $PIC_name,
        //     'periode' => $periode_info,
        //     'bagian' => $division
        // );
        
        if ($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
        } else {

            return $result;
        }

    }
}
