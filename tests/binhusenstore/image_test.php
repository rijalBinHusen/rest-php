<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class MyReportcartsTest extends TestCase
{
    private $url = "binhusenstore/";
    private $url_host_id = null;

    public function testPostEndpoint()
    {
        $http = new HttpCall($this->url . "image");
        // Define the request body

        $http->addJWTToken();

        $response = $http->getResponse("POST", "test_image.jpg");

        fwrite(STDERR, print_r($response, true));
        
        $convertToAssocArray = json_decode($response, true);

        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('filename', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        // $this->assertEquals(201, http_response_code());
        $this->url_host_id = $this->url . "image/" . $convertToAssocArray['id'];
    }

    // public function testPostEndpointFailed()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'image');

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("POST");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals('Failed to add cart, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPostEndpointFailed2()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'image');

    //     $response = $httpCallVar->getResponse("POST");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
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
    //     $this->assertEquals("Delete image success", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed401()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'image/loremipsum.jpg');

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
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'cart/loremipsum,jpg');

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Cart not found", $convertToAssocArray['message']);
    // }
}
