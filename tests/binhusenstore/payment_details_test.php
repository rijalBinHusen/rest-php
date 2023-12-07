<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class Payment_details_test extends TestCase
{
    private $url = "binhusenstore/";
    private $total_balance = 0;

    public function testTotalBalance()
    {

        $faker = Faker\Factory::create();
        $httpPostNewPayment = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_order' => $faker->numberBetween(10000, 10000000) . "_",
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
            'id_order_group' => $faker->numberBetween(10000, 10000000) . "_",
        );

        for($i = 0; $i <= 3; $i++) {

            $this->total_balance += $data['balance'];

            $httpPostNewPayment->setData($data);
            $httpPostNewPayment->addJWTToken();
            $response = $httpPostNewPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // // get payment by id order
        $id_order = $data['id_order'];
        $httpGetPaymentByIdOrder = new HttpCall($this->url .'payments?id_order=' .$id_order);
        $httpGetPaymentByIdOrder->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $httpGetPaymentByIdOrder->getResponse("GET");

        // fwrite(STDERR, print_r($response, true));
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date_payment', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('balance', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_paid', $convertToAssocArray['data'][0]);

        // compare total balance

        $total_balance_to_check = 0;
        foreach ($convertToAssocArray['data'] as $payment) {

            $total_balance_to_check += $payment['balance'];
        }

        $this->assertEquals($this->total_balance, $total_balance_to_check);
    }

    public function testPaymentMoreThanBalance()
    {

        $faker = Faker\Factory::create();
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        $id_order = $faker->numberBetween(1, 10000000) . "_";
        
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $id_order,
                'balance' => 100,
                'is_paid' => false,
                'id_order_group' => $faker->numberBetween(10000, 10000000) . "_",
            );

            $httpPostNewPayment->setData($data_to_send);
            $httpPostNewPayment->addJWTToken();

            $response = $httpPostNewPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // pay the bill
        $httpPostPutPayment = new HttpCall($this->url .'payment_mark_as_paid');
        $data_to_send2 = array(
            'id_order' => $id_order,
            'date_paid' => "2023-10-01",
            'balance' => 150
        );

        $httpPostPutPayment->setData($data_to_send2);
        $httpPostPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response2 = $httpPostPutPayment->getResponse("PUT");
        // fwrite(STDERR, print_r(PHP_EOL . "Response: " . $response2 . PHP_EOL, true));

        $convertToAssocArray = json_decode($response2, true);
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill

        $httpPostGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpPostGetPayment->addJWTToken();
        $response = $httpPostGetPayment->getResponse("GET");


        $convertToAssocArray = json_decode($response, true);

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(100, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("2023-10-01", $convertToAssocArray['data'][0]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $this->assertEquals(50, $convertToAssocArray['data'][1]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][1]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][1]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][2]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][2]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][2]['is_paid']);

        $this->assertEquals(50, $convertToAssocArray['data'][3]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][3]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][3]['is_paid']);
    }

    public function testPaymentLessThanBalance()
    {

        $faker = Faker\Factory::create();
        $id_order = $faker->numberBetween(1, 10000000) . "_";
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $id_order,
                'balance' => 100,
                'is_paid' => false,
            );

            $httpPostNewPayment->setData($data_to_send);
            $httpPostNewPayment->addJWTToken();

            $response = $httpPostNewPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // pay the bill
        $httpPutPayment = new HttpCall($this->url .'payment_mark_as_paid');
        
        $data_to_send = array(
            'id_order' => $id_order,
            'date_paid' => "2023-10-01",
            'balance' => 50
        );

        $httpPutPayment->setData($data_to_send);
        $httpPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutPayment->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill

        $httpPutPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpPutPayment->addJWTToken();
        $response = $httpPutPayment->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(50, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("2023-10-01", $convertToAssocArray['data'][0]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $this->assertEquals(150, $convertToAssocArray['data'][1]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][1]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][1]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][2]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][2]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][2]['is_paid']);
    }

    public function testPaymentEqualToBalance()
    {

        $faker = Faker\Factory::create();
        $id_order = $faker->numberBetween(1, 10000000) . "_";
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $id_order,
                'balance' => 100,
                'is_paid' => false,
            );

            $httpPostNewPayment->setData($data_to_send);
            $httpPostNewPayment->addJWTToken();

            $response = $httpPostNewPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // pay the bill
        $httpPutPayment = new HttpCall($this->url .'payment_mark_as_paid');
        
        $data_to_send = array(
            'id_order' => $id_order,
            'date_paid' => "2023-10-01",
            'balance' => 100
        );

        $httpPutPayment->setData($data_to_send);
        $httpPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutPayment->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill
        $httpGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpGetPayment->addJWTToken();

        $response = $httpGetPayment->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(100, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("2023-10-01", $convertToAssocArray['data'][0]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][1]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][1]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][1]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][2]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][2]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][2]['is_paid']);
    }

    public function testPaymentMoreThanBalance200()
    {

        $faker = Faker\Factory::create();
        $id_order = $faker->numberBetween(1, 10000000) . "_";
        $httpPostPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $id_order,
                'balance' => 100,
                'is_paid' => false,
            );

            $httpPostPayment->setData($data_to_send);
            $httpPostPayment->addJWTToken();

            $response = $httpPostPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // pay the bill
        $httpPutPayment = new HttpCall($this->url .'payment_mark_as_paid');
        
        $data_to_send = array(
            'id_order' => $id_order,
            'date_paid' => "2023-10-01",
            'balance' => 200
        );

        $httpPutPayment->setData($data_to_send);
        $httpPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutPayment->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill
        $httpGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpGetPayment->addJWTToken();
        $response = $httpGetPayment->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(100, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("2023-10-01", $convertToAssocArray['data'][0]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][1]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][1]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][1]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][2]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][2]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][2]['is_paid']);
    }

    public function testPayment250()
    {

        $faker = Faker\Factory::create();
        $id_order = $faker->numberBetween(1, 10000000) . "_";
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $id_order,
                'balance' => 100,
                'is_paid' => false,
            );

            $httpPostNewPayment->setData($data_to_send);
            $httpPostNewPayment->addJWTToken();

            $response = $httpPostNewPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // pay the bill
        $httpPutPayment = new HttpCall($this->url .'payment_mark_as_paid');
        
        $data_to_send = array(
            'id_order' => $id_order,
            'date_paid' => "2023-10-01",
            'balance' => 250
        );

        $httpPutPayment->setData($data_to_send);
        $httpPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutPayment->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill

        $httpGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpGetPayment->addJWTToken();
        $response = $httpGetPayment->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(100, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("2023-10-01", $convertToAssocArray['data'][0]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][1]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][1]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][1]['is_paid']);

        $this->assertEquals(50, $convertToAssocArray['data'][2]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][2]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][2]['is_paid']);

        $this->assertEquals(50, $convertToAssocArray['data'][3]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][3]['date_payment']);
        $this->assertEquals("0", $convertToAssocArray['data'][3]['is_paid']);
    }

    public function testPayment300()
    {

        $faker = Faker\Factory::create();
        $id_order = $faker->numberBetween(1, 10000000) . "_";
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $id_order,
                'balance' => 100,
                'is_paid' => false,
            );

            $httpPostNewPayment->setData($data_to_send);
            $httpPostNewPayment->addJWTToken();

            $response = $httpPostNewPayment->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // pay the bill
        $httpPutNewPayment = new HttpCall($this->url .'payment_mark_as_paid');
        
        $data_to_send = array(
            'id_order' => $id_order,
            'date_paid' => "2023-10-01",
            'balance' => 300
        );

        $httpPutNewPayment->setData($data_to_send);
        $httpPutNewPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutNewPayment->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill
        $httpGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpGetPayment->addJWTToken();
        $response = $httpGetPayment->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(100, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("2023-10-01", $convertToAssocArray['data'][0]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][1]['balance']);
        $this->assertEquals("2023-10-02", $convertToAssocArray['data'][1]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][1]['is_paid']);

        $this->assertEquals(100, $convertToAssocArray['data'][2]['balance']);
        $this->assertEquals("2023-10-03", $convertToAssocArray['data'][2]['date_payment']);
        $this->assertEquals("1", $convertToAssocArray['data'][2]['is_paid']);
    }
}
