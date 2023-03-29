<?php

class MyRestServerTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/";
    public function testGetEndpoint()
    {
        // Send a GET request to the /endpoint URL
        $response = file_get_contents($this->url);
        // Verify that the response is "I received a GET request."
        $this->assertEquals('I received a GET request.', $response);
    }

    public function testPostEndpoint()
    {
        // Define the request body
        $data = array('foo' => 'bar');
        $data_string = json_encode($data);
        
        // Set up the request headers
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        );
        
        // Send a POST request to the /endpoint URL with the request body
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Verify that the response is "Success"
        $this->assertEquals('I received a POST request ', $response);
    }

    public function testPutEndpoint()
    {
        // Define the request body
        $data = array('foo' => 'baz');
        $data_string = json_encode($data);
        
        // Set up the request headers
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        );
        
        // Send a PUT request to the /endpoint URL with the request body
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Verify that the response is "Success"
        $this->assertEquals('I received a PUT request.', $response);
    }

    public function testDeleteEndpoint()
    {
        // Send a DELETE request to the /endpoint URL
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Verify that the response is "Success"
        $this->assertEquals('I received a DELETE request.', $response);
    }
}
