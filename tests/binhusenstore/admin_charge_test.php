<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/user_test.php');

use PHPUnit\Framework\TestCase;

class Admin_charge_test extends TestCase
{
    private $url = "binhusenstore/admin_charge";
    private $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url);
        // Define the request body
        $data = array('admin_charge' => $faker->numberBetween(100, 10000));

        $this->data_posted = $data;

        $http->setData($data);

        $user = new User_test();
        $user->LoginAdmin();

        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testPostEndpointFailed()
    {
        $httpCallVar = new HttpCall($this->url);
        // Define the request body
        $data = array('admin_charge' => "Failed test");

        $httpCallVar->setData($data);

        $user = new User_test();
        $user->LoginAdmin();
        
        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add admin charge, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $httpCallVar = new HttpCall($this->url);
        // Define the request body

        $data = array('admin_charge' => "Failed test");

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

        $http = new HttpCall($this->url);
        
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($this->data_posted['admin_charge'] . PHP_EOL, true));
        // fwrite(STDERR, print_r($convertToAssocArray['admin_charge'], true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('admin_charge', $convertToAssocArray);
        $this->assertEquals($this->data_posted['admin_charge'], $convertToAssocArray['admin_charge']);
    }

    public function testGetByIdEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url);
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
        $httpCallVar = new HttpCall($this->url);
        // Define the request body
        $data = array('admin_charge' => 60000);

        $httpCallVar->setData($data);


        $user = new User_test();
        $user->LoginAdmin();
        
        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        // fwrite(STDERR, print_r($response, true));
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Update admin charge success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed400()
    {

        $httpCallVar = new HttpCall($this->url);
        // Define the request body
        $data = array('admin_charge__' => 'Failed test');

        $httpCallVar->setData($data);


        $user = new User_test();
        $user->LoginAdmin();
        
        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        // fwrite(STDERR, print_r($response, true));
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to update admin charge, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed401()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url);
        // Define the request body
        $data = array('name_admin_charge' => "Failed test");

        $httpCallVar->setData($data);

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }
}
