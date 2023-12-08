<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class Date_test extends TestCase
{
    private $url = "binhusenstore/";
    private $url_host_id = null;
    private $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "date");
        // Define the request body
        $data = array(
            'year' => $faker->numberBetween(1000, 9999),
            'date' => $faker->date("Y-m-d"),
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('year', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        // $this->assertEquals(201, http_response_code());
        $this->url_host_id = $this->url . "date/" . $convertToAssocArray['year'];
    }

    public function testPostEndpointFailed()
    {
        $httpCallVar = new HttpCall($this->url . 'date');
        // Define the request body
        $data = array('name' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add date, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $httpCallVar = new HttpCall($this->url . 'date');
        // Define the request body

        $data = array('date_name' => "Failed test");

        $httpCallVar->setData($data);

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    public function testGetEndpoint()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'dates');
        $http->addAccessCode("binhusenstore-access-code.txt");
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('year', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date', $convertToAssocArray['data'][0]);
    }

    public function testGetEndpointFailed()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'dates');
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }
    
    public function testPutEndpoint201()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('date' => "1234567890");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Update date success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed400()
    {
        $this->testPostEndpoint();
        $faker = Faker\Factory::create();

        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('name_date__' => $faker->firstName('female'));

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to update date, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed401()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url . 'date/loremipsum');
        // Define the request body
        $data = array('name_date' => "Failed test");

        $httpCallVar->setData($data);

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed404()
    {
        $httpCallVar = new HttpCall($this->url . 'date/loremipsum');
        // Define the request body
        $data = array('date' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("date not found", $convertToAssocArray['message']);
    }

    public function testDeleteEndpoint201()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url_host_id);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Delete date success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'date/loremipsum');

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed404()
    {
        $httpCallVar = new HttpCall($this->url . 'date/loremipsum');

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("date not found", $convertToAssocArray['message']);
    }
}
