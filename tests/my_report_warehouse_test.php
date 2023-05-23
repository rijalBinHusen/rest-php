<?php

require_once(__DIR__ . '/httpCall.php');
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class MyReportWarehousesTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
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
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], true);
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
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'warehouse/WAREHOUSE23010000');
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
        $this->assertEquals('Failed update warehouse, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'warehouse/WAREHOUSE23010000');
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
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'warehouse/WAREHOUSE23010000');
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
        $http = new HttpCall($this->url . 'warehouses/WAREHOUSE23010000');
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
        $http = new HttpCall($this->url . 'warehouses/WAREHOUSE23010000');
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
