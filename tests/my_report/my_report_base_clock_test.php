<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportBaseClockTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    private $idInserted = null;
    private $urlGets;
    private $urlPost;
    private $dataToInsert;
    private $dataToUpdate;
    private $urlDeleteByParents;

    public function __construct()
    {
        $faker = Faker\Factory::create();
        $parentId = $faker->text(7);
        $shiftStock = $faker->numberBetween(1, 3);

        $this->dataToInsert = array(
            'parent' => $parentId,
            'shift' => $shiftStock,
            'no_do' => $faker->text(15),
            'reg' => $faker->numberBetween(1, 10000),
            'start' => $faker->numberBetween(1, 10000),
            'finish' => $faker->numberBetween(1, 1000000),
            'rehat' => $faker->date('now'),
        );

        $this->urlPost = $this->url . 'base_clock/';
        $this->urlGets = $this->url . "base_clocks?parent=$parentId&shift=$shiftStock";
        $this->urlDeleteByParents =  $this->url . "base_clocks?parent=$parentId";

        $this->dataToUpdate = array(
            'reg' => $faker->numberBetween(1, 1000000),
            'start' => $faker->numberBetween(1, 1000000),
        );

    }
    
    public function testPostEndpoint()
    {
        $http = new HttpCall($this->urlPost);
        // Define the request body

        $http->setData($this->dataToInsert);
        // Define the request body
        $http->addJWTToken();
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, "\n failed post response :" .$response . "\n");
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->idInserted = $convertToAssocArray['id'];
    }

    public function testPostEndpointFailed()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost);
        // Define the request body
        $data = array(
            'supervisor_name' => "false false false",
            'supervisor_phone' => "false false false",
            'supervisor_warehouse' => "false false",
            'supervisor_shift' => 1,
        );

        $http->setData($data);

        $http->addJWTToken();
        
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed to add base clock, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $httpCallVar = new HttpCall($this->urlPost);
        // Define the request body

        $httpCallVar->setData($this->dataToInsert);
        
        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    public function testGetEndpoint()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGets);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('parent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('no_do', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('reg', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('start', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('finish', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('rehat', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetEndpointFailed()
    {
        $http = new HttpCall($this->urlGets);
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpoint()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlPost . $this->idInserted);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('parent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('no_do', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('reg', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('start', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('finish', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('rehat', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }
    
    public function testDeleteByParentEndpoint()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->urlDeleteByParents);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Delete base clock success", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpointFailed()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlPost . $this->idInserted);
        
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpointFailed2()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlPost . $this->idInserted . "11123");

        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Base clock not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body

        $httpCallVar->setData($this->dataToUpdate);
        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update base clock success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed()
    {
        $this->testPostEndpoint();
        // error 400
        $http = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body
        $data = array(
            'item_kode33' => '330303030',
            'item_name33' => 'false false false',
            'last_used33' => 'false false false'
        );

        $http->setData($data);

        $http->addJWTToken();
        
        $response = $http->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed to update base clock, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        $this->testPostEndpoint();
        // error 401
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body

        $httpCallVar->setData($this->dataToUpdate);
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed3()
    {
        $this->testPostEndpoint();
        // error 404
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted . '333');
        // Define the request body

        $httpCallVar->setData($this->dataToUpdate);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Base clock not found", $convertToAssocArray['message']);
    }

    public function testDeleteEndpoint()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Delete base clock success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed2()
    {
        $this->testPostEndpoint();
        // error 401
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed3()
    {
        $this->testDeleteEndpoint();
        // error 404
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Base clock not found", $convertToAssocArray['message']);
    }

}
