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
    private $balance_per_period = 0;

    public function testCreateOrder()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "order");
        $total_balance = $faker->numberBetween(100000, 110000);
        $balance_per_period = $faker->numberBetween(25000, 30000);
        // Define the request body
        $data = array(
            'date_order' => "2024-01-01",
            'id_group' => $faker->text(9),
            'is_group' => $faker->boolean(),
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => 'false',
            'title' => $faker->text(47),
            'total_balance' => $total_balance,
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => false,
            'start_date_payment' => "2024-01-01",
            'end_date_payment' => "2025-01-01",
            'balance_per_period' => $balance_per_period,
            'week_distance' => $faker->numberBetween(1, 5),
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
        $this->total_balance = $total_balance;
        $this->balance_per_period = $balance_per_period;
    }

    public function testTotalBalance()
    {
        $this->testCreateOrder();
        $faker = Faker\Factory::create();

        // get payment by id order
        $id_order = $this->order_id;
        $httpGetPaymentByIdOrder = new HttpCall($this->url . 'payments?id_order=' . $id_order);
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
        // Define the request body
        $data_to_send = array(
            'date_payment' => "2024-01-06",
            'id_order' => $this->order_id,
            'balance' => 50100,
            'is_paid' => true,
            'id_order_group' => "",
            'phone' => $this->phone
        );

        $httpPostNewPayment->setData($data_to_send);
        $httpPostNewPayment->addJWTToken();

        $response = $httpPostNewPayment->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($this->order_id, true));
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $httpPostGetPayment = new HttpCall($this->url . 'payments?id_order=' . $this->order_id);
        $httpPostGetPayment->addAccessCode("binhusenstore-access-code.txt");
        $response = $httpPostGetPayment->getResponse("GET");


        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($convertToAssocArray, true));

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals(50100, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $total_balance_to_check = 0;
        foreach ($convertToAssocArray['data'] as $payment) {

            $total_balance_to_check += $payment['balance'];
        }

        $this->assertEquals($this->total_balance, $total_balance_to_check);
    }

    public function testPaymentLessThanBalance()
    {
        $this->testCreateOrder();
        $httpPostNewPayment = new HttpCall($this->url . "payment");
        $balance = 100;
        // Define the request body
        $data_to_send = array(
            'date_payment' => "2024-01-06",
            'id_order' => $this->order_id,
            'balance' => $balance,
            'is_paid' => true,
            'id_order_group' => "",
            'phone' => $this->phone
        );

        $httpPostNewPayment->setData($data_to_send);
        $httpPostNewPayment->addJWTToken();

        $response = $httpPostNewPayment->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $httpPostGetPayment = new HttpCall($this->url . 'payments?id_order=' . $this->order_id);
        $httpPostGetPayment->addAccessCode("binhusenstore-access-code.txt");
        $response = $httpPostGetPayment->getResponse("GET");


        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($convertToAssocArray, true));

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals($balance, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $total_balance_to_check = 0;
        foreach ($convertToAssocArray['data'] as $payment) {

            $total_balance_to_check += $payment['balance'];
        }

        $this->assertEquals($this->total_balance, $total_balance_to_check);
    }

    public function testPaymentEqualToBalance()
    {


        $this->testCreateOrder();
        $httpPostNewPayment = new HttpCall($this->url . "payment");
        $balance = $this->balance_per_period;
        // Define the request body
        $data_to_send = array(
            'date_payment' => "2024-01-06",
            'id_order' => $this->order_id,
            'balance' => $balance,
            'is_paid' => true,
            'id_order_group' => "",
            'phone' => $this->phone
        );

        $httpPostNewPayment->setData($data_to_send);
        $httpPostNewPayment->addJWTToken();

        $response = $httpPostNewPayment->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $httpPostGetPayment = new HttpCall($this->url . 'payments?id_order=' . $this->order_id);
        $httpPostGetPayment->addAccessCode("binhusenstore-access-code.txt");
        $response = $httpPostGetPayment->getResponse("GET");


        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($convertToAssocArray, true));

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals($balance, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $total_balance_to_check = 0;
        foreach ($convertToAssocArray['data'] as $payment) {

            $total_balance_to_check += $payment['balance'];
        }

        $this->assertEquals($this->total_balance, $total_balance_to_check);
    }

    public function testPaymentMoreThanBalance200()
    {


        $this->testCreateOrder();
        $httpPostNewPayment = new HttpCall($this->url . "payment");
        $balance = $this->balance_per_period * 2;
        // Define the request body
        $data_to_send = array(
            'date_payment' => "2024-01-06",
            'id_order' => $this->order_id,
            'balance' => $balance,
            'is_paid' => true,
            'id_order_group' => "",
            'phone' => $this->phone
        );

        $httpPostNewPayment->setData($data_to_send);
        $httpPostNewPayment->addJWTToken();

        $response = $httpPostNewPayment->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $httpPostGetPayment = new HttpCall($this->url . 'payments?id_order=' . $this->order_id);
        $httpPostGetPayment->addAccessCode("binhusenstore-access-code.txt");
        $response = $httpPostGetPayment->getResponse("GET");


        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($convertToAssocArray, true));

        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('data', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertEquals($balance, $convertToAssocArray['data'][0]['balance']);
        $this->assertEquals("1", $convertToAssocArray['data'][0]['is_paid']);

        $total_balance_to_check = 0;
        foreach ($convertToAssocArray['data'] as $payment) {

            $total_balance_to_check += $payment['balance'];
        }

        $this->assertEquals($this->total_balance, $total_balance_to_check);
    }
}
