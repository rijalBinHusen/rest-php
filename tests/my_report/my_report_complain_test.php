<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportComplainTest extends PHPUnit_Framework_TestCase
{
    private $url = "myreport/";
    private $idInserted = null;
    private $urlGets;
    private $urlPost;

    public function __construct()
    {
        $this->urlGets = $this->url . 'complains/';
        $this->urlPost = $this->url . 'complain/';
    }
    
    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost);
        // Define the request body
        $data = array(
            'periode' => $faker->firstName('female'),
            'head_spv_id' => $faker->firstName('female'),
            'dl' => $faker->numberBetween(10000, 1000000),
            'inserted' => $faker->numberBetween(10000, 1000000),
            'masalah' => $faker->firstName('female'),
            'supervisor_id' => $faker->firstName('female'),
            'parent' => $faker->numberBetween(10000, 1000000),
            'pic' => $faker->firstName('female'),
            'solusi' => $faker->firstName('female'),
            'is_status_done' => $faker->boolean(),
            'sumber_masalah' => $faker->firstName('female'),
            'type' => $faker->firstName('female'),
            'is_count' => $faker->numberBetween(0, 100)
        );

        $http->setData($data);
        // Define the request body
        $http->addJWTToken();
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response . "\n", true));
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
            'supervisor_name' => $faker->firstName('female'),
            'supervisor_phone' => $faker->numberBetween(100000, 1000000),
            'supervisor_warehouse' => $faker->firstName('female'),
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
        $this->assertEquals('Failed to add complain, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost);
        // Define the request body
        $data = array(
            'supervisor_name' => $faker->firstName('female'),
            'supervisor_phone' => $faker->numberBetween(100000, 1000000),
            'supervisor_warehouse' => $faker->firstName('female'),
            'supervisor_shift' => 1,
        );

        $httpCallVar->setData($data);
        
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
        $http = new HttpCall($this->urlGets . "?limit=10");
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('periode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('inserted', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('parent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_status_done', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('type', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_count', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals(10, count($convertToAssocArray['data']));
    }

    public function testGetEndpointFailed()
    {
        $http = new HttpCall($this->urlGets . "?limit=10");
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
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('periode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('inserted', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('parent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_status_done', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('type', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_count', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
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
        $this->assertEquals("Complain not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint()
    {
        $this->testPostEndpoint();
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body
        $data = array(
            'periode' => $faker->numberBetween(1000, 10000000),
            'head_spv_id' => $faker->firstName('female'),
            'dl' => $faker->numberBetween(1000, 10000000)
        );

        $httpCallVar->setData($data);
        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update complain success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed()
    {
        $this->testPostEndpoint();
        // error 400
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body
        $data = array(
            'item_kode33' => $faker->firstName('female'),
            'item_name33' => $faker->firstName('female'),
            'last_used33' => $faker->numberBetween(1000, 10000000)
        );

        $http->setData($data);

        $http->addJWTToken();
        
        $response = $http->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed to update complain, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        $this->testPostEndpoint();
        // error 401
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body
        $data = array(
            'warehouse_name' => $faker->firstName('female'),
            'warehouse_group' => $faker->firstName('female'),
        );

        $httpCallVar->setData($data);
        
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
        $data = array(
            'periode' => $faker->firstName('female'),
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Complain not found", $convertToAssocArray['message']);
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
        $this->assertEquals("Delete complain success", $convertToAssocArray['message']);
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
        $this->testPostEndpoint();
        // error 404
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted . '333');

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Complain not found", $convertToAssocArray['message']);
    }

}
