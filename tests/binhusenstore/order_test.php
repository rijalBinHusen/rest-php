<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class Order_test extends TestCase
{
    private $url = "binhusenstore/";
    private $url_host_id = null;
    private  $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "order");
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => $faker->text(9),
            'is_group' => $faker->boolean(),
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => 'false',
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        // $this->assertEquals(201, http_response_code());
        $this->url_host_id = $this->url . "order/" . $convertToAssocArray['id'];
    }

    public function testPostEndpointFailed400()
    {
        $httpCallVar = new HttpCall($this->url . 'order');
        // Define the request body
        $data = array(
            'date_order_' => 'Failed test',
            'id_group' => 'Failed test',
            'is_group' => 'Failed test',
            'id_product' => 'Failed test',
            'name_of_customer' => 'Failed test',
            'sent' => 'false',
            'title' => 'Failed test',
            'total_balance' => 'Failed test'
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add order, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'order');
        // Define the request body

        $data = array('title' => "Failed test");

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

        $http = new HttpCall($this->url . 'orders');
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_product', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('name_of_customer', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('title', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_balance', $convertToAssocArray['data'][0]);
    }

    public function testGetEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'orders');
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpoint()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url_host_id);
        // Send a GET request to the /endpoint URL
        
        $http->addJWTToken();
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_product', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('name_of_customer', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('title', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_balance', $convertToAssocArray['data'][0]);
    }

    public function testGetByIdEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url_host_id);
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpointFailed404()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->url . 'order/SDFLSKDFJ');

        $http->addJWTToken();
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Order not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint201()
    {
        $this->testPostEndpoint();
        
        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('title' => "Updated");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Update order success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed400()
    {
        $this->testPostEndpoint();

        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('date_order__' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to update order, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed401()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');
        // Define the request body
        $data = array('date_order' => "Failed test");

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
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');
        // Define the request body
        $data = array('date_order' => "Failed test");

        $httpCallVar->setData($data);
        
        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Order not found", $convertToAssocArray['message']);
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
        $this->assertEquals("Delete order success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');

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
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Order not found", $convertToAssocArray['message']);
    }
}
