<?php
use PHPUnit\Framework\TestCase;
// Class yang mau di TEST.
require_once( __DIR__. '/../utils/generator_id.php');
// require_once "Wordcount.php";

// Class untuk run Testing.
class SimpleTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratorId()
    {
        $year = 2023; // replace with the year you want to generate
        $start_date = new DateTime("$year-01-02");
        $end_date = new DateTime("$year-12-31");

        $current_date = $start_date;
        $week = 1;
        while ($current_date <= $end_date) {
            // echo $current_date->format("Y-m-d") . "<br>";
            // Kita masukan parameter 4 kata, yang harusnya dapat output 4.
            $TestSentence = generateIdWithCustomDate("SUPER_22110000", $current_date->format("Y-m-d")); // 4 Kata ..
            // $WordCount = $Wc->countWords($TestSentence);
            
            // Kita assert equal, ekspektasi nya harus 4, jika benar berarti Wordcount berfungsi dengan baik.
            $weekId = $week < 10 ? "0". $week : $week;
            $expect = "SUPER_23". $weekId ."0000";
            // expect record
            $this->assertEquals($expect, $TestSentence);
            $current_date->modify("+7 day");
            $week = $week + 1;
        }
            
    }
}