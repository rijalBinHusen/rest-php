<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/user_test.php');

use PHPUnit\Framework\TestCase;

class Payment_details_test extends TestCase
{
    private $url = "binhusenstore/";
    private $total_balance = 0;
    private $order_id = "";
    private $phone = "";

    public function testCreateOrder() {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "order");
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => $faker->text(9),
            'is_group' => $faker->boolean(),
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => 'false',
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);

        $user = new User_test();
        $user->LoginAdmin();

        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->order_id = $convertToAssocArray['id'];
        $this->phone = $data['phone'];
    }

    public function testTotalBalance()
    {
        $this->testCreateOrder();
        $faker = Faker\Factory::create();
        $httpPostNewPayment = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_order' => $this->order_id,
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
            'id_order_group' => $faker->numberBetween(10000, 10000000) . "_",
        );

        // create new payment
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

        // get payment by id order
        $id_order = $data['id_order'];
        $httpGetPaymentByIdOrder = new HttpCall($this->url .'payments?id_order=' .$id_order);
        $httpGetPaymentByIdOrder->addAccessCode("binhusenstore-access-code.txt");
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

        $this->testCreateOrder();
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        $id_order = $this->order_id;
        
        // create payment
        for($i = 1; $i <= 3; $i++) {
            // Define the request body
            $data_to_send = array(
                'date_payment' => "2023-10-0" . $i,
                'id_order' => $this->order_id,
                'balance' => 100,
                'is_paid' => false,
                'id_order_group' => "",
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
            'balance' => 150,
            'phone' => $this->phone
        );

        $httpPostPutPayment->setData($data_to_send2);

        $httpPostPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response2 = $httpPostPutPayment->getResponse("PUT");
        // fwrite(STDERR, print_r(PHP_EOL . "id order: " . $id_order . PHP_EOL . "phone: " . $this->phone, true));

        $convertToAssocArray = json_decode($response2, true);
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill
        $httpPostGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpPostGetPayment->addAccessCode("binhusenstore-access-code.txt");
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
        $this->testCreateOrder();
        $id_order = $this->order_id;
        $httpPostNewPayment = new HttpCall($this->url . "payment");

        // reset total balance
        $this->total_balance = 300;
        
        // create payment
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
            'balance' => 50,
            'phone' => $this->phone
        );

        $httpPutPayment->setData($data_to_send);
        $httpPutPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutPayment->getResponse("PUT");
        // fwrite(STDERR, print_r($response, true));

        $convertToAssocArray = json_decode($response, true);
        $this->assertArrayHasKey('success', $convertToAssocArray, $response);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill

        $httpPutPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpPutPayment->addAccessCode("binhusenstore-access-code.txt");
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

        $this->testCreateOrder();
        $id_order = $this->order_id;
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
            'balance' => 100,
            'phone' => $this->phone,
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
        $httpGetPayment->addAccessCode("binhusenstore-access-code.txt");

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
        
        $this->testCreateOrder();
        $id_order = $this->order_id;
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
            'balance' => 200,
            'phone' => $this->phone
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
        $httpGetPayment->addAccessCode("binhusenstore-access-code.txt");
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

        $this->testCreateOrder();
        $id_order = $this->order_id;
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
            'balance' => 250,
            'phone' => $this->phone
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
        $httpGetPayment->addAccessCode("binhusenstore-access-code.txt");
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

        $this->testCreateOrder();
        $id_order = $this->order_id;
        $phone_order = $this->phone;
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
            'balance' => 300,
            'phone' => $phone_order
        );

        $httpPutNewPayment->setData($data_to_send);
        $httpPutNewPayment->addJWTToken();

        // Send a GET request to the /endpoint URL
        $response = $httpPutNewPayment->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($id_order . $phone_order, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Update payment success", $convertToAssocArray['message']);


        // get all bill
        $httpGetPayment = new HttpCall($this->url . 'payments?id_order=' . $id_order);
        $httpGetPayment->addAccessCode("binhusenstore-access-code.txt");
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
