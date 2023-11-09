<?php
require_once(__DIR__ . '/httpCall.php');
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class AccessCodeTest extends PHPUnit_Framework_TestCase {
    private $url = "access_code/";
    private $accessCodeInserted = null;

    //create access code success
    public function testCreateAccessCode() {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . 'create');
        $new_access_code = $faker->numberBetween(100000, 999999);

        // define request body
        $data = array(
            'source_name' => 'tests',
            'code' => $new_access_code,
        );

        $http->setData($data);
        $http->addJWTToken();
        $reponse = $http->getResponse("POST");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals('Your code is set', $convertToAssocArray['message']);
        $this->accessCodeInserted = $new_access_code;
    }

    // create access code failed
    public function testCreateAccessCodeFailed() {
        $http = new HttpCall($this->url . 'create');

        // define request body
        $data = array(
            'source_name' => 'tests',
        );

        $http->setData($data);
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

        // define request body
        $data = array(
            'source_name' => 'tests',
        );

        $http->setData($data);
        $reponse = $http->getResponse("POST");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Request body invalid', $convertToAssocArray['message']);
    }

    // validate code failed2
    public function testValidateAccessCodeFailed2() {
        $this->testCreateAccessCode();
        $http = new HttpCall($this->url . 'validate');

        // define request body
        $data = array(
            'source_name' => 'tests',
            'code' => $this->accessCodeInserted . "111",
        );

        $http->setData($data);
        $reponse = $http->getResponse("POST");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Access code or resorce name invalid', $convertToAssocArray['message']);
    }

    // validate code failed3
    public function testValidateAccessCodeFailed3() {
        $this->testCreateAccessCode();
        $http = new HttpCall($this->url . 'validate');

        // define request body
        $data = array(
            'source_name' => 'tests22333',
            'code' => $this->accessCodeInserted,
        );

        $http->setData($data);
        $reponse = $http->getResponse("POST");

        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Access code or resorce name invalid', $convertToAssocArray['message']);
    }

    // validate code success
    public function testValidateAccessCode() {
        $this->testCreateAccessCode();
        $http = new HttpCall($this->url . 'validate');

        // define request body
        $data = array(
            'source_name' => 'tests',
            'code' => $this->accessCodeInserted,
        );

        $http->setData($data);

        $reponse = $http->getResponse("POST");

        // fwrite(STDERR, print_r($reponse, true));
        $convertToAssocArray = json_decode($reponse, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals('Your code is valid', $convertToAssocArray['message']);
    }
}