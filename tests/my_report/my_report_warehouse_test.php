<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportWarehousesTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    private $url_host_id = null;
    public function testGetEndpoint()
    {
        $http = new HttpCall($this->url . 'warehouses');
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], true);
    }

    public function testGetEndpointFailed()
    {
        $http = new HttpCall($this->url . 'warehouses');
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "warehouse");
        // Define the request body
        $data = array(
            'warehouse_name' => $faker->name('female'),
            'warehouse_group' => $faker->name('female'),
            'warehouse_supervisors' => $faker->name('female')
        );

        $http->setData($data);
        // Define the request body
        $http->addJWTToken();
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], true);
        $this->url_host_id = $this->url . 'warehouse/' . $convertToAssocArray['id'];
        // fwrite(STDERR, print_r("INSERTED ID: " . $this->url_host_id, true));]
        // fwrite(STDERR, print_r($this->url_host_id, true));
    }

    public function testPostEndpointFailed()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'warehouse');
        // Define the request body
        $data = array(
            'warehouse_name' => $faker->name('female'),
            'warehouse_group' => $faker->name('female'),
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed add warehouse, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed()
    {
        $this->testPostEndpoint();
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url_host_id);
        // fwrite(STDERR, print_r("OUT END POINT FAILED" . $this->url_host_id, true));
        // Define the request body
        $data = array(
            'warehouse_nameddd' => $faker->name('female'),
            'warehouse_groupddd' => $faker->name('female'),
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed to update warehouse, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        $this->testPostEndpoint();
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array(
            'warehouse_name' => $faker->name('female'),
            'warehouse_group' => $faker->name('female'),
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

    public function testPutEndpoint()
    {
        $this->testPostEndpoint();
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array(
            'warehouse_name' => $faker->name('female'),
            'warehouse_group' => $faker->name('female'),
        );

        $httpCallVar->setData($data);
        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update warehouse success", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpoint()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->url_host_id);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], true);
    }

    public function testGetByIdEndpointFailed()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->url_host_id);
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }
}
