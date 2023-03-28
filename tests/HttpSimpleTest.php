<?php
// use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
// Class untuk run Testing.
class MyApiTest extends PHPUnit_Framework_TestCase
{
    public function testGetEndpoint()
    {
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'http://localhost/rest-php/');
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaderLine('content-type');
        $body = $response->getBody()->getContents();
        $expectedResponse = '{"message": "Hello World!"}';
        $this->assertEquals(200, $statusCode);
        $this->assertEquals('application/json', $contentType);
        $this->assertEquals($expectedResponse, $body);
    }
}