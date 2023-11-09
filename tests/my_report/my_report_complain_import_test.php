<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportComplainImportTest extends PHPUnit_Framework_TestCase
{
    private $url = "myreport/";
    private $idInserted = null;
    private $urlGets;
    private $urlPost;

    public function __construct()
    {
        $this->urlGets = $this->url . 'complains_import/';
        $this->urlPost = $this->url . 'complain_import/';
    }
    
    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost);
        // Define the request body
        $data = array(
            'customer' => $faker->firstName('female'),
            'do_' => $faker->firstName('female'),
            'gudang' => $faker->numberBetween(10000, 1000000),
            'item' => $faker->numberBetween(10000, 1000000),
            'kabag' => $faker->firstName('female'),
            'nomor_SJ' => $faker->firstName('female'),
            'nopol' => $faker->numberBetween(10000, 1000000),
            'real_' => $faker->firstName('female'),
            'row_' => $faker->firstName('female'),
            'spv' => $faker->boolean(),
            'tally' => $faker->firstName('female'),
            'tanggal_bongkar' => $faker->firstName('female'),
            'tanggal_info' => $faker->numberBetween(0, 100),
            'tanggal_komplain' => $faker->numberBetween(0, 100),
            'tanggal_SJ' => $faker->numberBetween(0, 100),
            'type_' => $faker->numberBetween(0, 100),
            'is_inserted' => $faker->boolean(),
        );

        $http->setData($data);
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
        $this->assertEquals('Failed to add complain import, check the data you sent', $convertToAssocArray['message']);
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
        $this->assertArrayHasKey('customer', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('do_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('gudang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('kabag', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('nomor_SJ', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('nopol', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('real_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('row_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('spv', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tally', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_bongkar', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_info', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_komplain', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_SJ', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('type_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_inserted', $convertToAssocArray['data'][0]);
        $this->assertEquals(10, count($convertToAssocArray['data']));
        $this->assertEquals(true, $convertToAssocArray['success']);
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
        $this->assertArrayHasKey('customer', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('do_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('gudang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('kabag', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('nomor_SJ', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('nopol', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('real_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('row_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('spv', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tally', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_bongkar', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_info', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_komplain', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_SJ', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('type_', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_inserted', $convertToAssocArray['data'][0]);
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
        $this->assertEquals("Complain import not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint()
    {
        $this->testPostEndpoint();
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body
        $data = array(
            'customer' => $faker->firstName('female'),
            'do_' => $faker->firstName('female'),
            'item' => $faker->firstName('female')
        );

        $httpCallVar->setData($data);
        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update complain import success", $convertToAssocArray['message']);
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
        $this->assertEquals('Failed to update complain import, check the data you sent', $convertToAssocArray['message']);
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
            'customer' => $faker->firstName('female'),
            'do_' => $faker->firstName('female'),
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Complain import not found", $convertToAssocArray['message']);
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
        $this->assertEquals("Delete complain import success", $convertToAssocArray['message']);
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
        $this->assertEquals("Complain import not found", $convertToAssocArray['message']);
    }

}
