<?php

require_once (__DIR__ . '/../utils/summary_db.php');
require_once (__DIR__ . '/../utils/generator_id.php');

class SummaryDatabaseTest extends PHPUnit_Framework_TestCase {
    
    public function testSummary() {

        $summary = new SummaryDatabase("my_report_warehouse");
        
        // get last id from summary
        $last_id = $summary->getLastId();
        fwrite(STDERR, print_r("Last id: " .$last_id ." before update \n", true));

        // get next id with generator id
        $next_id_from_generator = generateId($last_id);
        // get next id from summary
        $next_id_from_summary = $summary->getNextId();
        // assert equal next id
        $this->assertEquals($next_id_from_generator, $next_id_from_summary);

        // update last id
        $summary->updateLastId($next_id_from_summary);

        // get last id from summary
        $last_id_after_generate_next_id = $summary->getLastId();
        // assert equals
        $this->assertEquals($next_id_from_generator, $last_id_after_generate_next_id);
        
        fwrite(STDERR, print_r("Last id: " . $last_id_after_generate_next_id ." after update\n", true));


    }
}