<?php

require_once(__DIR__ . '/httpCall.php');
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class MyReportComplainImportTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    private $idInserted = null;
    private $urlGets;
    private $urlPost;
    private $dataToInsert;
    private $dataToUpdate;

    public function __construct()
    {
        $parentId = $faker->text(10);
        $shiftStock = $faker->numberBetween(1, 3);
        $faker = Faker\Factory::create();
        $this->dataToInsert = array(
            'parent' => $parentId,
            'shift' => $shiftStock,
            'item' => $faker->text(15),
            'awal' => $faker->numberBetween(1, 10000),
            'in_stock' => $faker->numberBetween(1, 10000),
            'out_stock' => $faker->numberBetween(1, 1000000),
            'date_in' => $faker->date('now'),
            'plan_out' => $faker->numberBetween(1, 1000000),
            'date_out' => $faker->date('now'),
            'date_end' => $faker->date('now'),
            'real_stock' => $faker->numberBetween(1, 1000000),
            'problem' => "",
        );

        $this->urlPost = $this->url . 'base_stock/';
        $this->urlGets = $this->url . "base_stocks?parent=$parentId&shift=$shiftStock";

        $this->dataToUpdate = array(
            'awal' => $faker->numberBetween(1, 1000000),
            'in_stock' => $faker->numberBetween(1, 1000000),
            'out_stock' => $faker->numberBetween(1, 1000000),
            'real_stock' => $faker->numberBetween(1, 1000000)
        );

    }
    
    public function testPostEndpoint()
    {
        $http = new HttpCall($this->urlPost);
        // Define the request body

        $http->setData($this->dataToInsert);
        // Define the request body
        $http->addJWTToken();
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray->data);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->idInserted = $convertToAssocArray->data['id'];
    }

    public function testPostEndpointFailed()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost);
        // Define the request body
        $data = array(
            'supervisor_name' => "false false false",
            'supervisor_phone' => "false false false",
            'supervisor_warehouse' => "false false",
            'supervisor_shift' => 1,
        );

        $http->setData($data);

        $http->addJWTToken();
        
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed to add base stock, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $httpCallVar = new HttpCall($this->urlPost);
        // Define the request body

        $httpCallVar->setData($this->dataToInsert);
        
        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    public function testGetEndpoint()
    {
        $http = new HttpCall($this->urlGets);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('parent', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('shift', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('item', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('awal', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('in_stock', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('out_stock', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('date_in', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('plan_out', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('date_out', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('date_end', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('real_stock', $convertToAssocArray->data[0]);
        $this->assertArrayHasKey('problem', $convertToAssocArray->data[0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetEndpointFailed()
    {
        $http = new HttpCall($this->urlGets);
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpoint()
    {
        $http = new HttpCall($this->urlPost . $this->idInserted);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('parent', $convertToAssocArray->data);
        $this->assertArrayHasKey('shift', $convertToAssocArray->data);
        $this->assertArrayHasKey('item', $convertToAssocArray->data);
        $this->assertArrayHasKey('awal', $convertToAssocArray->data);
        $this->assertArrayHasKey('in_stock', $convertToAssocArray->data);
        $this->assertArrayHasKey('out_stock', $convertToAssocArray->data);
        $this->assertArrayHasKey('date_in', $convertToAssocArray->data);
        $this->assertArrayHasKey('plan_out', $convertToAssocArray->data);
        $this->assertArrayHasKey('date_out', $convertToAssocArray->data);
        $this->assertArrayHasKey('date_end', $convertToAssocArray->data);
        $this->assertArrayHasKey('real_stock', $convertToAssocArray->data);
        $this->assertArrayHasKey('problem', $convertToAssocArray->data);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetByIdEndpointFailed()
    {
        $http = new HttpCall($this->urlPost . $this->idInserted);
        
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpointFailed2()
    {
        $http = new HttpCall($this->urlPost . $this->idInserted . "11123");

        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Base stock not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint()
    {
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body

        $httpCallVar->setData($this->dataToUpdate);
        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update base file stock", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed()
    {
        // error 400
        $http = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body
        $data = array(
            'item_kode33' => '330303030',
            'item_name33' => 'false false false',
            'last_used33' => 'false false false'
        );

        $http->setData($data);

        $http->addJWTToken();
        
        $response = $http->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed update to base stock, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        // error 401
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        // Define the request body

        $httpCallVar->setData($this->dataToUpdate);
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed3()
    {
        // error 404
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted . '333');
        // Define the request body

        $httpCallVar->setData($this->dataToUpdate);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Base stock not found", $convertToAssocArray['message']);
    }

    public function testDeleteEndpoint()
    {
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Delete base stock success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed2()
    {
        // error 401
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed3()
    {
        // error 404
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Base stock not found", $convertToAssocArray['message']);
    }

}
