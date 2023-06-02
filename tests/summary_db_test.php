<?php

require_once (__DIR__ . '/../utils/summary_db.php');

class SummaryDatabaseTest extends PHPUnit_Framework_TestCase {
    public function FailedtestSummary() {
        $summary = new SummaryDatabase("my_report_document");

        fwrite(STDERR, print_r($summary->getData(), true));
        $this->assertEquals(10, $summary->getLastId());
    }
}