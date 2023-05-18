<?php

// require_once(__DIR__ . '/httpCall.php');
// require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

// class MyReportSupervisorsTest extends PHPUnit_Framework_TestCase
// {
//     private $url = "http://localhost/rest-php/myreport/";
    
//     public function testPostEndpoint()
//     {
//         $faker = Faker\Factory::create();
//         $http = new HttpCall($this->url . "problem");
//         // Define the request body
//         $data = array(
//             'warehouse_id' => $faker->name('female'),
//             'supervisor_id' => $faker->name('female'),
//             'head_spv_id' => $faker->name('female'),
//             'item_kode' => 1,
//             'tanggal_muali' => 33333,
//             'pic' => $faker->name('female'),
//             "dl" => $faker->name('female'),
//             "masalah" => $faker->name('female'),
//             "sumber_masalah" => $faker->name('female'),
//             "solusi" => $faker->name('female'),
//             "solusi_panjang" => $faker->name('female'),
//             "dl_panjang" => $faker->name('female'),
//             "pic_panjang" => $faker->name('female'),
//             "tanggal_selesai" => $faker->name('female'),
//             "shift_selesai" => $faker->name('female')
//         );

//         $http->setData($data);
//         // Define the request body
//         $http->addJWTToken();
//         $response = $http->getResponse("POST");

//         $convertToAssocArray = json_decode($response, true);

//         // fwrite(STDERR, print_r($response, true));
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('data', $convertToAssocArray);
//         $this->assertArrayHasKey('id', $convertToAssocArray->data);
//         $this->assertEquals($convertToAssocArray['success'], true);
//     }

//     public function testPostEndpointFailed()
//     {
//         $faker = Faker\Factory::create();
//         $httpCallVar = new HttpCall($this->url . 'problem');
//         // Define the request body
//         $data = array(
//             'supervisor_name' => $faker->name('female'),
//             'supervisor_phone' => $faker->$faker->numberBetween(100000, 1000000),
//             'supervisor_warehouse' => $faker->name('female'),
//             'supervisor_shift' => 1,
//         );

//         $httpCallVar->setData($data);

//         $http->addJWTToken();
        
//         $response = $httpCallVar->getResponse("POST");

//         $convertToAssocArray = json_decode($response, true);
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals('Failed add supervisor, check the data you sent', $convertToAssocArray['message']);
//     }

//     public function testPostEndpointFailed2()
//     {
//         $faker = Faker\Factory::create();
//         $httpCallVar = new HttpCall($this->url . 'problem');
//         // Define the request body
//         $data = array(
//             'supervisor_name' => $faker->name('female'),
//             'supervisor_phone' => $faker->$faker->numberBetween(100000, 1000000),
//             'supervisor_warehouse' => $faker->name('female'),
//             'supervisor_shift' => 1,
//         );

//         $httpCallVar->setData($data);
        
//         $response = $httpCallVar->getResponse("POST");

//         $convertToAssocArray = json_decode($response, true);
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
//     }

//     public function testGetEndpoint()
//     {
//         $http = new HttpCall($this->url . 'supervisors');
//         $http->addJWTToken();
//         // Send a GET request to the /endpoint URL
//         $response = $http->getResponse("GET");
        
//         $convertToAssocArray = json_decode($response, true);
//         // fwrite(STDERR, print_r($convertToAssocArray, true));
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('data', $convertToAssocArray);
//         $this->assertArrayHasKey('id', $convertToAssocArray->data[0]);
//         $this->assertArrayHasKey('supervisor_name', $convertToAssocArray->data[0]);
//         $this->assertArrayHasKey('supervisor_warehouse', $convertToAssocArray->data[0]);
//         $this->assertArrayHasKey('supervisor_shift', $convertToAssocArray->data[0]);
//         $this->assertArrayHasKey('is_disabled', $convertToAssocArray->data[0]);
//         $this->assertEquals($convertToAssocArray['success'], true);
//     }

//     public function testGetEndpointFailed()
//     {
//         $http = new HttpCall($this->url . 'supervisors');
        
//         $convertToAssocArray = json_decode($response, true);
//         // fwrite(STDERR, print_r($convertToAssocArray, true));
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
//     }

//     public function testGetByIdEndpoint()
//     {
//         $http = new HttpCall($this->url . 'supervisor/SPV23010000');
//         $http->addJWTToken();
//         // Send a GET request to the /endpoint URL
//         $response = $http->getResponse("GET");
        
//         $convertToAssocArray = json_decode($response, true);
//         // fwrite(STDERR, print_r($convertToAssocArray, true));
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('data', $convertToAssocArray);
//         $this->assertArrayHasKey('id', $convertToAssocArray->data);
//         $this->assertArrayHasKey('supervisor_name', $convertToAssocArray->data);
//         $this->assertArrayHasKey('supervisor_warehouse', $convertToAssocArray->data);
//         $this->assertArrayHasKey('supervisor_shift', $convertToAssocArray->data);
//         $this->assertArrayHasKey('is_disabled', $convertToAssocArray->data);
//         $this->assertEquals(true, $convertToAssocArray['success']);
//     }

//     public function testGetByIdEndpointFailed()
//     {
//         $http = new HttpCall($this->url . 'supervisor/SPV23010000');
        
//         $convertToAssocArray = json_decode($response, true);
//         // fwrite(STDERR, print_r($convertToAssocArray, true));
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
//     }

//     public function testGetByIdEndpointFailed2()
//     {
//         $http = new HttpCall($this->url . 'supervisor/SDFLSKDFJ');

//         $http->addJWTToken();
        
//         $convertToAssocArray = json_decode($response, true);
//         // fwrite(STDERR, print_r($convertToAssocArray, true));
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals("Supervisor not found", $convertToAssocArray['message']);
//     }

//     public function testPutEndpointFailed()
//     {
//         $faker = Faker\Factory::create();
//         $httpCallVar = new HttpCall($this->url . 'supervisor/SPV23010000');
//         // Define the request body
//         $data = array(
//             'warehouse_nameddd' => $faker->name('female'),
//             'warehouse_groupddd' => $faker->name('female'),
//         );

//         $httpCallVar->setData($data);

//         $http->addJWTToken();
        
//         $response = $httpCallVar->getResponse("PUT");

//         $convertToAssocArray = json_decode($response, true);
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals('Failed update supervisor, check the data you sent', $convertToAssocArray['message']);
//     }

//     public function testPutEndpointFailed2()
//     {
//         $faker = Faker\Factory::create();
//         $httpCallVar = new HttpCall($this->url . 'warehouse/WAREHOUSE23010000');
//         // Define the request body
//         $data = array(
//             'warehouse_name' => $faker->name('female'),
//             'warehouse_group' => $faker->name('female'),
//         );

//         $httpCallVar->setData($data);
        
//         $response = $httpCallVar->getResponse("PUT");

//         $convertToAssocArray = json_decode($response, true);
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(false, $convertToAssocArray['success']);
//         $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
//     }

//     public function testPutEndpoint()
//     {
//         $faker = Faker\Factory::create();
//         $httpCallVar = new HttpCall($this->url . 'warehouse/WAREHOUSE23010000');
//         // Define the request body
//         $data = array(
//             'supervisor_name' => $faker->name('female'),
//             'supervisor_shift' => 3,
//         );

//         $httpCallVar->setData($data);
//         $httpCallVar->addJWTToken();
        
//         $response = $httpCallVar->getResponse("PUT");

//         $convertToAssocArray = json_decode($response, true);
//         // Verify that the response same as expected
//         $this->assertArrayHasKey('success', $convertToAssocArray);
//         $this->assertArrayHasKey('message', $convertToAssocArray);
//         $this->assertEquals(true, $convertToAssocArray['success']);
//         $this->assertEquals("Update supervisor success", $convertToAssocArray['message']);
//     }
// }
