<?php

require_once(__DIR__ . '/httpCall.php');
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class MyReportProblemTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    private $idInserted = null;
    private $urlGetByPeriode;
    private $urlGetByStatus;
    private $urlPost;
    private $dataToInsert;
    private $dataToUpdate;

    public function __construct()
    {
        $faker = Faker\Factory::create();
        $periode = $faker->date('now');

        $this->dataToInsert = array(
            'collected' => false,
            'approval' => false,
            'status' => 0,
            'shared' => 0,
            'finished' => 0,
            'total_do' => 0,
            'total_kendaraan' => 0,
            'total_waktu' => 0,
            'base_report_file' => 0,
            'is_finished' => 0,
            'supervisor_id' => 0,
            'periode' => $periode,
            'shift' => 1,
            'head_spv_id' => $faker->text(10),
            'warehouse_id' => $faker->text(11),
            'is_generated_document' => 0
        );

        $this->urlPost = $this->url . 'document/';
        $this->urlGetByPeriode = $this->url . "documents/byperiode?periode1=$periode&periode2=$periode";
        $this->urlGetByStatus = $this->url . "documents/bystatus?status=0";

        $this->dataToUpdate = array(
            'collected' => $faker->date('now'),
            'approval' => $faker->date('now'),
            'status' => 2
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
        $this->assertEquals('Failed to add document, check the data you sent', $convertToAssocArray['message']);
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

    public function testGetEndPointByPeriode()
    {
        $http = new HttpCall($this->urlGetByPeriode);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('collected', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('approval', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('status', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shared', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('finished', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_do', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_kendaraan', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_waktu', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('base_report_file', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('periode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_generated_document', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetEndPointByPeriodeFailed()
    {
        $http = new HttpCall($this->urlGetByPeriode);
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetEndPointByStatus()
    {
        $http = new HttpCall($this->urlGetByStatus);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('collected', $convertToAssocArray['data']);
        $this->assertArrayHasKey('approval', $convertToAssocArray['data']);
        $this->assertArrayHasKey('status', $convertToAssocArray['data']);
        $this->assertArrayHasKey('shared', $convertToAssocArray['data']);
        $this->assertArrayHasKey('finished', $convertToAssocArray['data']);
        $this->assertArrayHasKey('total_do', $convertToAssocArray['data']);
        $this->assertArrayHasKey('total_kendaraan', $convertToAssocArray['data']);
        $this->assertArrayHasKey('total_waktu', $convertToAssocArray['data']);
        $this->assertArrayHasKey('base_report_file', $convertToAssocArray['data']);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data']);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('periode', $convertToAssocArray['data']);
        $this->assertArrayHasKey('shift', $convertToAssocArray['data']);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('is_generated_document', $convertToAssocArray['data']);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetEndPointByStatusFailed()
    {
        $http = new HttpCall($this->urlGetByStatus);
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
        $this->assertArrayHasKey('id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('collected', $convertToAssocArray['data']);
        $this->assertArrayHasKey('approval', $convertToAssocArray['data']);
        $this->assertArrayHasKey('status', $convertToAssocArray['data']);
        $this->assertArrayHasKey('shared', $convertToAssocArray['data']);
        $this->assertArrayHasKey('finished', $convertToAssocArray['data']);
        $this->assertArrayHasKey('total_do', $convertToAssocArray['data']);
        $this->assertArrayHasKey('total_kendaraan', $convertToAssocArray['data']);
        $this->assertArrayHasKey('total_waktu', $convertToAssocArray['data']);
        $this->assertArrayHasKey('base_report_file', $convertToAssocArray['data']);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data']);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('periode', $convertToAssocArray['data']);
        $this->assertArrayHasKey('shift', $convertToAssocArray['data']);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data']);
        $this->assertArrayHasKey('is_generated_document', $convertToAssocArray['data']);
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
        $this->assertEquals("Document not found", $convertToAssocArray['message']);
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
        $this->assertEquals("Update document success", $convertToAssocArray['message']);
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
        $this->assertEquals('Failed to update document, check the data you sent', $convertToAssocArray['message']);
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
        $this->assertEquals("Problem not found", $convertToAssocArray['message']);
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
        $this->assertEquals("Delete document success", $convertToAssocArray['message']);
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
        $httpCallVar = new HttpCall($this->urlPost . $this->idInserted . '333');

        $httpCallVar->addJWTToken();
        
        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Document not found", $convertToAssocArray['message']);
    }

    public function testGetLastDate()
    {
        $http = new HttpCall($this->urlPost . "/last_date");
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('date', $convertToAssocArray['data']);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetLastDateFailed()
    {
        $http = new HttpCall($this->urlPost . "/last_date");
        
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
}
