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
        // get komplain, my_report_complain periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_complain = "SELECT * FROM my_report_complain WHERE periode BETWEEN $periode1 AND $periode2";
        // retrieve problem, my_report_problem tanggal_mulai $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_problem = "SELECT * FROM my_report_problem WHERE tanggal_mulai BETWEEN $periode1 AND $periode2";
        // retrieve field problem, my_report_field_problem periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_field_problem = "SELECT * FROM my_report_field_problem WHERE periode BETWEEN $periode1 AND $periode2";
        // retrieve case, my_report_cases periode between $periode1 and $periode2 by supervisor_id or head_spv_id
        $query_case = "SELECT * FROM my_report_cases WHERE periode BETWEEN $periode1 AND $periode2";
        if ($head_supervisor_id) {

            $query_document = $query_document . " AND head_spv_id = '$head_supervisor_id'";
            $query_complain = $query_complain . " AND head_spv_id = '$head_supervisor_id'";
            $query_problem = $query_problem . " AND head_spv_id = '$head_supervisor_id'";
            $query_field_problem = $query_field_problem . " AND head_spv_id = '$head_supervisor_id'";
            $query_case = $query_case . " AND head_spv_id = '$head_supervisor_id'";
        } else {

            $query_document = $query_document . " AND supervisor_id = '$supervisor_id'";
            $query_complain = $query_complain . " AND supervisor_id = '$supervisor_id'";
            $query_problem = $query_problem . " AND supervisor_id = '$supervisor_id'";
            $query_field_problem = $query_field_problem . " AND supervisor_id = '$supervisor_id'";
            $query_case = $query_case . " AND supervisor_id = '$supervisor_id'";
        }

        $result_documents  = $this->database->sqlQuery($query_document)->fetchAll(PDO::FETCH_ASSOC);

        $is_documents_not_exists = count($result_documents) === 0;
        if ($is_documents_not_exists) {
            return $result;
        }

        $result_complains = $this->database->sqlQuery($query_complain)->fetchAll(PDO::FETCH_ASSOC);
        $result_problems = $this->database->sqlQuery($query_problem)->fetchAll(PDO::FETCH_ASSOC);
        $result_field_problems = $this->database->sqlQuery($query_field_problem)->fetchAll(PDO::FETCH_ASSOC);
        $result_cases = $this->database->sqlQuery($query_case)->fetchAll(PDO::FETCH_ASSOC);

        $result['documents'] = $result_documents;
        $result['complains'] = $result_complains;
        $result['problems'] = $result_problems;
        $result['field_problems'] = $result_field_problems;
        $result_cases = $result_cases;

        if ($this->database->is_error !== null) {

            $this->is_success = $this->database->is_error;
        } else {

            return $result;
        }

    }
}
