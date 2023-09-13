<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportProductTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/binhusenstore/";
    private $url_host_id = null;
    private $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "product");
        // Define the request body
        $data = array(
            'name' => $faker->city(),
            'categories' => $faker->text(30),
            'price' => $faker->numberBetween(10000, 100000),
            'weight' => $faker->numberBetween(100, 1000),
            'images' => $faker->text(100),
            'description' => $faker->text(150),
            'default_total_week' => $faker->numberBetween(20, 60),
            'is_available' => $faker->boolean()
        );

        $this->data_posted = $data;

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
        $this->url_host_id = $this->url . "product/" . $convertToAssocArray['id'];
    }

    public function testPostEndpointFailed400()
    {
        $httpCallVar = new HttpCall($this->url . 'product');
        // Define the request body
        $data = array(
            'name_' => "Failed test",
            'categories' => "Failed test",
            'price' => "Failed test",
            'weight' => "Failed test",
            'images' => "Failed test",
            'description' => "Failed test",
            'default_total_week' => "Failed test",
            'is_available' => "Failed test" 
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add product, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'product');
        // Define the request body

        $data = array('name' => "Failed test");

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

        $http = new HttpCall($this->url .'products');
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
        $this->assertArrayHasKey('name', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('categories', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('price', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('weight', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('images', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('description', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('default_total_week', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_available', $convertToAssocArray['data'][0]);
    }

    public function testGetEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'products');
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
        $this->assertArrayHasKey('name', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('categories', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('price', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('weight', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('images', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('description', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('default_total_week', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_available', $convertToAssocArray['data'][0]);
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
        $http = new HttpCall($this->url . 'product/SDFLSKDFJ');

        $http->addJWTToken();
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("product not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint201()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('price' => 100000);

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Update product success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed400()
    {
        $this->testPostEndpoint();

        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('price__' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to update product, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed401()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url . 'product/loremipsum');
        // Define the request body
        $data = array('price' => "Failed test");

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
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url . 'product/loremipsum');
        // Define the request body
        $data = array('price' => "Failed test");

        $httpCallVar->addJWTToken();
        $httpCallVar->setData($data);

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("product not found.", $convertToAssocArray['message']);
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
        $this->assertEquals("Delete product success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'product/loremipsum');

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
        $httpCallVar = new HttpCall($this->url . 'product/loremipsum');

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("product not found.", $convertToAssocArray['message']);
    }
}
