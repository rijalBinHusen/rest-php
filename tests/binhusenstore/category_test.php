<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportCategoryTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/binhusenstore/";
    private $url_host_id = null;
    private $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "category");
        // Define the request body
        $data = array('name_category' => $faker->text(30));

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        // $this->assertEquals(201, http_response_code());
        $this->url_host_id = $this->url . "category/" . $convertToAssocArray['id'];
    }

    // public function testPostEndpointFailed()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'category');
    //     // Define the request body
    //     $data = array('name' => "Failed test");

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("POST");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('Failed add category, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPostEndpointFailed2()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'category');
    //     // Define the request body

    //     $data = array('category_name' => "Failed test");

    //     $httpCallVar->setData($data);

    //     $response = $httpCallVar->getResponse("POST");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    // }

    // public function testGetEndpoint()
    // {
    //     $this->testPostEndpoint();

    //     $http = new HttpCall($this->url . 'categories');
    //     $http->addJWTToken();
    //     // Send a GET request to the /endpoint URL
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('name_category', $convertToAssocArray['data'][0]);
    // }

    // public function testGetEndpointFailed()
    // {
    //     $this->testPostEndpoint();

    //     $http = new HttpCall($this->url . 'categories');
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testGetByIdEndpoint()
    // {
    //     $this->testPostEndpoint();

    //     $http = new HttpCall($this->url_host_id);
    //     $http->addJWTToken();
    //     // Send a GET request to the /endpoint URL
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('name_category', $convertToAssocArray['data'][0]);
    // }

    // public function testGetByIdEndpointFailed401()
    // {
    //     $this->testPostEndpoint();

    //     $http = new HttpCall($this->url_host_id);
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testGetByIdEndpointFailed404()
    // {
    //     $this->testPostEndpoint();
    //     $http = new HttpCall($this->url . 'category/SDFLSKDFJ');

    //     $http->addJWTToken();
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Category not found", $convertToAssocArray['message']);
    // }

    // public function testPutEndpoint201()
    // {
    //     $this->testPostEndpoint();
    //     $faker = Faker\Factory::create();
    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array('name_category' => $faker->firstName("male"));

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Update category success", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed400()
    // {
    //     $this->testPostEndpoint();
    //     $faker = Faker\Factory::create();

    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array('name_category__' => $faker->firstName('female'));

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('Failed to update category, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed401()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'category/loremipsum');
    //     // Define the request body
    //     $data = array('name_category' => "Failed test");

    //     $httpCallVar->setData($data);

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed404()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'category/loremipsum');
    //     // Define the request body
    //     $data = array('name_category' => "Failed test");

    //     $httpCallVar->addJWTToken();
    //     $httpCallVar->setData($data);

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("category not found.", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpoint201()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url_host_id);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Delete category success", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed401()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'category/loremipsum');

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed404()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'category/loremipsum');

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("category not found.", $convertToAssocArray['message']);
    // }
}
