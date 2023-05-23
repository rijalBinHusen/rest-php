<?php

require_once(__DIR__ . '/httpCall.php');
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class MyReportHeadSupervisorTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    
    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "head_spv");
        // Define the request body
        $data = array(
            'head_name' => $faker->name('female'),
            'head_phone' => $faker->$faker->numberBetween(100000, 1000000),
            'head_shift' => 1,
            'is_disabled' => true
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
        $this->assertArrayHasKey('id', $convertToAssocArray->data);
        $this->assertEquals($convertToAssocArray['success'], true);
    }

    public function testPostEndpointFailed()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'head_spv');
        // Define the request body
        $data = array(
            'head_name' => $faker->name('female'),
            'head_phone' => $faker->$faker->numberBetween(100000, 1000000),
            'head_shift' => 1,
        );

        $httpCallVar->setData($data);

        $http->addJWTToken();
        
        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed add head supervisor, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'head_spv');
        // Define the request body
        $data = array(
            'supervisor_name' => $faker->name('female'),
            'supervisor_phone' => $faker->$faker->numberBetween(100000, 1000000),
            'supervisor_warehouse' => $faker->name('female'),
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
        $http = new HttpCall($this->url . 'heads_spv');
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('head_name', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('head_phone', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('head_shift', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('is_disabled', $convertToAssocArray->data[0]);
        $this->assertEquals($convertToAssocArray['success'], true);
    }

    public function testGetEndpointFailed()
    {
        $http = new HttpCall($this->url . 'heads_spv');
        
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
        $http = new HttpCall($this->url . 'head_spv/HEADSPV23030001');
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('head_name', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('head_phone', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('head_shift', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('is_disabled', $convertToAssocArray->data[0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetByIdEndpointFailed()
    {
        $http = new HttpCall($this->url . 'head_spv/HEADSPV23030001');
        
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
        $http = new HttpCall($this->url . 'head_spv/SDFLSKDFJ');

        $http->addJWTToken();
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Head supervisor not found", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'head_spv/HEADSPV23030001');
        // Define the request body
        $data = array(
            'warehouse_nameddd' => $faker->name('female'),
            'warehouse_groupddd' => $faker->name('female'),
        );

        $httpCallVar->setData($data);

        $http->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed update head supervisor, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'head_spv/WAREHOUSE23010000');
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
        $httpCallVar = new HttpCall($this->url . 'head_spv/HEAD230300001');
        // Define the request body
        $data = array(
            'head_name' => $faker->name('female'),
            'head_shift' => 3,
        );

        $httpCallVar->setData($data);
        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update supervisor success", $convertToAssocArray['message']);
    }
}
