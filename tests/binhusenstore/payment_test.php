<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class Payment_test extends TestCase
{
    private $url = "binhusenstore/";
    private $url_host_id = null;
    private $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(5),
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
            'id_order_group' => $faker->text(5)
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertedToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertedToAssocArray);
        $this->assertArrayHasKey('id', $convertedToAssocArray, $response);
        $this->assertEquals(true, $convertedToAssocArray['success']);
        // $this->assertEquals(201, http_response_code());
        $this->url_host_id = $this->url . "payment/" . $convertedToAssocArray['id'];
    }

    public function testPostEndpointInvalidNumberBalance()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(5),
            'balance' => "Not number",
            'is_paid' => false,
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add payment, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointInvalidBooleanIsPaid()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(5),
            'balance' => "Not number",
            'is_paid' => "Not boolean",
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add payment, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointInvalidDate()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => "2023-02-30",
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(5),
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add payment, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointInvalidDate2()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => "sakfjhaslkfjhalskjdfhwoieruqpieru",
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(5),
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add payment, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed400()
    {
        $httpCallVar = new HttpCall($this->url . 'payment');
        // Define the request body
        $data = array(
            'datepayment' => "Failed test",
            'id_payment' => "Failed test",
            'id_order' => "Failed test",
            'balance' => "Failed test",
            'is_paid' => false,
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add payment, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'payment');
        // Define the request body

        $data = array('id_order' => "Failed test");

        $httpCallVar->setData($data);

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    }

    public function testGetEndpoint()
    {
        $this->testPostEndpoint();

        $id_order = $this->data_posted['id_order'];

        $http = new HttpCall($this->url .'payments?id_order=' .$id_order);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($id_order, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date_payment', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('balance', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_paid', $convertToAssocArray['data'][0]);
    }

    public function testGetEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'payments');
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetEndpointFailed404()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'payments?id_order=loremipsumdolor');
        
        $http->addJWTToken();

        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Payments not found", $convertToAssocArray['message']);
    }

    public function testGetEndpointFailed404_()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'payments');
        
        $http->addJWTToken();

        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Payments not found", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpoint()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url_host_id);
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date_payment', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('balance', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_paid', $convertToAssocArray['data'][0]);
    }

    public function testGetByIdEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url_host_id);
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpointFailed404()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->url . 'payment/SDFLSKDFJ');

        $http->addJWTToken();
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Payment not found", $convertToAssocArray['message']);
    }

    // public function testPutEndpoint201()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array('balance' => 100000);

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     fwrite(STDERR, print_r($response, true));
    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Update payment success", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointInvalidDate()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array(
    //         'balance' => 100000,
    //         'date_payment' => "2023-02-30",
    //     );

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     // update data on server
    //     $httpCallVar->getResponse("PUT");

    //     // validate data
    //     $getData = $httpCallVar->getResponse("GET");
        
    //     $convertToAssocArray = json_decode($getData, true);
        
        
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     // fwrite(STDERR, print_r($convertToAssocArray, true));

    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('date_payment', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('id_order', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('balance', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('is_paid', $convertToAssocArray['data'][0]);
    //     $this->assertNotEquals($data['date_payment'], $convertToAssocArray['data'][0]['date_payment']);
    // }

    // public function testPutEndpointInvalidDate2()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array(
    //         'balance' => 100000,
    //         'date_payment' => "sakdfjhasfkjas;dflkjasd;fkjsdlfkj",
    //     );

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     // update data on server
    //     $httpCallVar->getResponse("PUT");
        
    //     $getData = $httpCallVar->getResponse("GET");
        
    //     $convertToAssocArray = json_decode($getData, true);

    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('date_payment', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('id_order', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('balance', $convertToAssocArray['data'][0]);
    //     $this->assertArrayHasKey('is_paid', $convertToAssocArray['data'][0]);
    //     $this->assertNotEquals($data['date_payment'], $convertToAssocArray['data'][0]['date_payment']);
    // }

    // public function testPutEndpointFailed400()
    // {
    //     $this->testPostEndpoint();

    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array('balance__' => "Failed test");

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('Failed to update payment, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed401()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'payment/loremipsum');
    //     // Define the request body
    //     $data = array('date_payment' => "Failed test");

    //     $httpCallVar->setData($data);

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed404()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'payment/loremipsum');
    //     // Define the request body
    //     $data = array('date_payment' => "2023-03-23");

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Payment not found", $convertToAssocArray['message']);
    // }

    public function testDeleteEndpoint201()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url_host_id);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Delete payment success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'payment/loremipsum');

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed404()
    {
        $httpCallVar = new HttpCall($this->url . 'payment/loremipsum');

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Payment not found", $convertToAssocArray['message']);
    }
}
