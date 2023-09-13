<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class Note_app_test extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/";
    private $idInserted = null;
    private $urlGets;
    private $urlPost;

    public function __construct()
    {
        $this->urlGets = $this->url . 'notes/';
        $this->urlPost = $this->url . 'note/';
    }
    
    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost);
        // Define the request body
        $data = array('isi' => $faker->text(1000));

        $http->setData($data);
        // Define the request body
        $http->addJWTToken();
        
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response ."\n", true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals(true, is_string($convertToAssocArray['id']));
        $this->idInserted = $convertToAssocArray['id'];
    }

    public function testPostEndpointFailed()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->urlPost);
        // Define the request body
        $data = array(
            'invalid_key' => $faker->firstName('female'),
        );

        $http->setData($data);

        $http->addJWTToken();
        
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Failed to add note, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed2()
    {
        $faker = Faker\Factory::create();
        $httpCallVar = new HttpCall($this->urlPost);
        // Define the request body
        $data = array('supervisor_name' => $faker->firstName('female'));

        $httpCallVar->setData($data);
        
        $response = $httpCallVar->getResponse("POST");

        // write(STDERR, print_r($response, true));
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
        // write(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('isi', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testGetEndpointFailed()
    {
        $http = new HttpCall($this->urlGets);
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // write(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetEndpointByKeyword()
    {
        $http = new HttpCall($this->urlGets . "?search=lorem");
        $http->addJWTToken();
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // write(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('isi', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }


    public function testGetByIdEndpoint()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->urlPost . $this->idInserted);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");
        
        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('isi', $convertToAssocArray['data'][0]);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    // public function testGetByIdEndpointFailed()
    // {
    //     $this->testPostEndpoint();
    //     $http = new HttpCall($this->urlPost . $this->idInserted);
        
    //     // Send a GET request to the /endpoint URL
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testGetByIdEndpointFailed2()
    // {
    //     $this->testPostEndpoint();
    //     $http = new HttpCall($this->urlPost . $this->idInserted . "121111");

    //     $http->addJWTToken();
    //     // Send a GET request to the /endpoint URL
    //     $response = $http->getResponse("GET");
        
    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals("Base item not found", $convertToAssocArray['message']);
    // }

    // public function testPutEndpoint()
    // {
    //     $this->testPostEndpoint();
    //     $faker = Faker\Factory::create();
    //     $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
    //     // Define the request body
    //     $data = array(
    //         'isi' => $faker->text(100)
    //     );

    //     $httpCallVar->setData($data);
    //     $httpCallVar->addJWTToken();
        
    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);
    //     $this->assertEquals("Update base item success", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed()
    // {
    //     $this->testPostEndpoint();
    //     // error 400
    //     $faker = Faker\Factory::create();
    //     $http = new HttpCall($this->urlPost . $this->idInserted);
    //     // Define the request body
    //     $data = array(
    //         'item_kode33' => $faker->firstName('female'),
    //         'item_name33' => $faker->firstName('female'),
    //         'last_used33' => $faker->numberBetween(1000, 10000000)
    //     );

    //     $http->setData($data);

    //     $http->addJWTToken();
        
    //     $response = $http->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals('Failed to update base item, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed2()
    // {
    //     $this->testPostEndpoint();
    //     // error 401
    //     $faker = Faker\Factory::create();
    //     $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
    //     // Define the request body
    //     $data = array(
    //         'warehouse_name' => $faker->firstName('female'),
    //         'warehouse_group' => $faker->firstName('female'),
    //     );

    //     $httpCallVar->setData($data);
        
    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed3()
    // {
    //     $this->testPostEndpoint();
    //     // error 404
    //     $faker = Faker\Factory::create();
    //     $httpCallVar = new HttpCall($this->urlPost . $this->idInserted . '333');
    //     // Define the request body
    //     $data = array(
    //         'item_kode' => $faker->firstName('female'),
    //         'item_name' => $faker->firstName('female'),
    //     );

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();
        
    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals("Base item not found", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpoint()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);

    //     $httpCallVar->addJWTToken();
        
    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);
    //     $this->assertEquals("Delete base item success", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed2()
    // {
    //     $this->testPostEndpoint();
    //     // error 401
    //     $httpCallVar = new HttpCall($this->urlPost . $this->idInserted);
        
    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed3()
    // {
    //     $this->testPostEndpoint();
    //     // error 404
    //     $httpCallVar = new HttpCall($this->urlPost . $this->idInserted . '333');

    //     $httpCallVar->addJWTToken();
        
    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);
    //     $this->assertEquals("Base item not found", $convertToAssocArray['message']);
    // }

}
