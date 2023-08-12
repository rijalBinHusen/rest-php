<?php
// require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/report_controller.php");

Flight::route('GET /myreport/report/weekly_report', function () {
    // $user = new User();
    // $is_token_valid = $user->is_valid_token();

    // if(!$is_token_valid) {
    //     return;
    // }

    $myreport_report = new My_report_report();
    $myreport_report->get_weekly_report();
});