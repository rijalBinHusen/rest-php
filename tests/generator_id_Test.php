<?php
// require_once(__DIR__ . '/../vendor/autoload.php');
// Path to run ./vendor/bin/phpunit --bootstrap vendor/autoload.php FileName.php
// Butuh Framework PHPUnit
use PHPUnit\Framework\TestCase;

// Class yang mau di TEST.
require_once( __DIR__. '/../model/generator_id.php');
// require_once "Wordcount.php";

// Class untuk run Testing.
class SimpleTest extends PHPUnit_Framework_TestCase
{
    public function testCountWords()
    {
        // Kita pakai class yang mau kita test.
        // $Wc = new WordCount();

        // Kita masukan parameter 4 kata, yang harusnya dapat output 4.
        $TestSentence = generateId("SUPER_22110000"); // 4 Kata ..
        // $WordCount = $Wc->countWords($TestSentence);

        // Kita assert equal, ekspektasi nya harus 4, jika benar berarti Wordcount berfungsi dengan baik.
        $this->assertEquals("SUPER_23110000", $TestSentence); 
    }
}