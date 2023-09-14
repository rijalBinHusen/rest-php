<?php
require_once(__DIR__ . "/../../Users/user_controller.php");
require_once(__DIR__ . "/document_controller.php");

Flight::route('POST /myreport/document', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_document = new My_report_document();
        $myreport_document->add_document();    
    }
});

Flight::route('GET /myreport/documents/byperiode', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {

        
        $myreport_document = new My_report_document();
        $myreport_document->get_document_by_periode();
    }
});

Flight::route('GET /myreport/documents/bystatus', function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_document = new My_report_document();
        $myreport_document->get_document_by_status();
    }
});

Flight::route("GET /myreport/document/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_document = new My_report_document();
        $myreport_document->get_document_by_id($id);    
    }
});

Flight::route("PUT /myreport/document/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_document = new My_report_document();
        $myreport_document->update_document_by_id($id);
    }
});

Flight::route("DELETE /myreport/document/@id", function ($id) {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_document = new My_report_document();
        $myreport_document->remove_document_by_id($id);
    }
});

Flight::route("GET /myreport/document_/last_date", function () {
    $user = new User("users");
    $is_token_valid = $user->is_valid_token();

    if($is_token_valid) {
        
        $myreport_document = new My_report_document();
        $myreport_document->last_date();
    }
});