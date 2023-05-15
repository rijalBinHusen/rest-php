<?php

require_once(__DIR__ . '/httpCall.php');
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class MyReportWarehousesTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    public function testGetEndpoint()
    {
        // Send a GET request to the /endpoint URL
        $response = file_get_contents($this->url . 'warehouses');
        
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], true);
    }

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->url . 'warehouse');
        // Define the request body
        $data = array(
            'warehouse_name' => $faker->name('female'),
            'warehouse_group' => $faker->name('female'),
            'warehouse_supervisors' => $faker->name('female')
        );

        $httpCallVar->setData($data);
        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
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
        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], false);
        $this->assertEquals($convertToAssocArray['message'], 'Failed add warehouse, check the data you sent');
    }

    // public function testPutEndpoint()
    // {
    //     // Define the request body
    //     $data = array('foo' => 'baz');
    //     $data_string = json_encode($data);
        
    //     // Set up the request headers
    //     $headers = array(
    //         'Content-Type: application/json',
    //         'Content-Length: ' . strlen($data_string)
    //     );
        
    //     // Send a PUT request to the /endpoint URL with the request body
    //     $ch = curl_init($this->url);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
        
    //     // Verify that the response is "Success"
    //     $this->assertEquals('I received a PUT request.', $response);
    // }

    // public function testDeleteEndpoint()
    // {
    //     // Send a DELETE request to the /endpoint URL
    //     $ch = curl_init($this->url);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
        
    //     // Verify that the response is "Success"
    //     $this->assertEquals('I received a DELETE request.', $response);
    // }
}
