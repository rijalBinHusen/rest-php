<?php
require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class Access_code_test extends TestCase {
    private $url = "binhusenstore/access_code/";
    private $accessCodeInserted = null;

    //create access code success
    public function testCreateAccessCode() {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url);
        $new_access_code = $faker->numberBetween(100000, 999999);

        // define request body
        $data = array('code' => $new_access_code);

        $http->setData($data);
        $http->addJWTToken();
        $reponse = $http->getResponse("POST");

        // fwrite(STDERR, print_r($reponse . PHP_EOL, true));
        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals('Your code is set', $convertToAssocArray['message']);
        $this->accessCodeInserted = $new_access_code;

        // save token to a .txt file
        $myfile = fopen("binhusenstore-access-code.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $new_access_code);
        fclose($myfile);
    }

    // create access code failed
    public function testCreateAccessCodeFailed() {
        $http = new HttpCall($this->url);

        $http->addJWTToken();
        $reponse = $http->getResponse("POST");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Request body invalid', $convertToAssocArray['message']);
    }

    // validate code failed
    public function testValidateAccessCodeFailed() {
        $http = new HttpCall($this->url . 'validate');

        $reponse = $http->getResponse("GET");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    // validate code failed2
    public function testValidateAccessCodeFailed2() {
        $this->testCreateAccessCode();
        $http = new HttpCall($this->url . 'validate');

        $reponse = $http->getResponse("GET");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    // validate code failed3
    public function testValidateAccessCodeFailed3() {
        $this->testCreateAccessCode();
        $http = new HttpCall($this->url . 'validate');

        $reponse = $http->getResponse("GET");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    // validate code success
    public function testValidateAccessCode() {
        $this->testCreateAccessCode();
        $http = new HttpCall($this->url . 'validate');

        $http->addAccessCode("binhusenstore-access-code.txt");
        $reponse = $http->getResponse("GET");

        // fwrite(STDERR, print_r($reponse, true));
        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals('Your code is valid', $convertToAssocArray['message']);
    }
}