<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportProblemTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/myreport/";
    private $idInserted = null;
    private $urlGetByPeriode;
    private $urlGetByStatus;
    private $urlGetBySupervisor;
    private $urlGetByWarehouseAndItem;
    private $urlPost;
    private $dataToInsert;
    private $dataToUpdate;

    public function __construct()
    {
        $faker = Faker\Factory::create();
        $shiftStock = $faker->numberBetween(1, 3);
        $tanggalMulai = $faker->numberBetween(1, 10000);
        $supervisor_id = $faker->text(6);
        $warehouse_id = $faker->text(7);
        $item_kode = $faker->text(5);

        $this->dataToInsert = array(
            'warehouse_id' => $warehouse_id,
            'supervisor_id' => $supervisor_id,
            'head_spv_id' => $faker->text(15),
            'item_kode' => $item_kode,
            'tanggal_mulai' => $tanggalMulai,
            'shift_mulai' => $shiftStock,
            'pic' => $faker->date('now'),
            'dl' => $faker->date('now'),
            'masalah' => $faker->date('now'),
            'sumber_masalah' => $faker->date('now'),
            'solusi' => $faker->date('now'),
            'solusi_panjang' => $faker->date('now'),
            'dl_panjang' => $faker->date('now'),
            'pic_panjang' => $faker->date('now'),
            'tanggal_selesai' => $faker->date('now'),
            'shift_selesai' => $faker->date('now'),
            'is_finished' => false
        );

        $this->urlPost = $this->url . 'problem/';
        $this->urlGetByPeriode = $this->url . "problems/byperiode?periode1=$tanggalMulai&periode2=$tanggalMulai";
        $this->urlGetByStatus = $this->url . "problems/bystatus?status=0";
        $this->urlGetBySupervisor = $this->url . "problems/bysupervisor?supervisor_id=$supervisor_id";
        $this->urlGetByWarehouseAndItem = $this->url . "problems/bywarehouseanditem?warehouse_id=$warehouse_id&item_kode=$item_kode";

        $this->dataToUpdate = array(
            'masalah' => $faker->text(100),
            'sumber_masalah' => $faker->text(200),
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

        fwrite(STDERR, print_r("\n" .$response ."\n", true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->idInserted = $convertToAssocArray['id'];
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
        $this->assertEquals('Failed to add base clock, check the data you sent', $convertToAssocArray['message']);
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
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGetByPeriode);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r("\n testGetEndPointByPeriode: " . $this->urlGetByPeriode . "\n", true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item_kode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_selesai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetEndPointByPeriodeFailed()
    {
        $this->testPostEndpoint();
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
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGetByStatus);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item_kode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_selesai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetEndPointByStatusFailed()
    {
        $this->testPostEndpoint();
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

    public function testGetEndPointBySupervisor()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGetBySupervisor);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r("\n URL Get by supervisor : " .$this->urlGetBySupervisor. "\n", true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item_kode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_selesai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetEndPointBySupervisorFailed()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGetBySupervisor);
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetEndPointByWarehouseAndItem()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGetByWarehouseAndItem);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        $messageToShow = "\n" . "URL Get By Warehouse And Item" . $this->urlGetByWarehouseAndItem . "\n";
        // fwrite(STDERR, print_r($messageToShow, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item_kode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_selesai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetEndPointByWarehouseAndItemFailed()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlGetByWarehouseAndItem);
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
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlPost . $this->idInserted);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r("\n" . $this->urlPost . $this->idInserted ."\n", true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('warehouse_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('supervisor_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('head_spv_id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('item_kode', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('shift_mulai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sumber_masalah', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('solusi_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('dl_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('pic_panjang', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('tanggal_selesai', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_finished', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetByIdEndpointFailed()
    {
        $this->testPostEndpoint();
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
        $this->testPostEndpoint();
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
        $this->assertEquals("Problem record not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint()
    {
        $this->testPostEndpoint();
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
        $this->assertEquals("Update problem success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed()
    {
        $this->testPostEndpoint();
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
        $this->assertEquals('Failed to update problem, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed2()
    {
        $this->testPostEndpoint();
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
        $this->testPostEndpoint();
        // error 404
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
        $this->assertEquals("Problem record not found", $convertToAssocArray['message']);
    }

}
