<?php

class MyRestServerTest extends PHPUnit_Framework_TestCase
{
    public function testGetEndpoint()
    {
        // Send a GET request to the /endpoint URL
        $response = file_get_contents('http://localhost/rest-php/');
        // Verify that the response is "I received a GET request."
        $this->assertEquals('I received a GET request.', $response);
    }
}
