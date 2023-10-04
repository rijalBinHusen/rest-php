<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/fakerphp/faker/src/autoload.php');

class MyReportPaymentTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/binhusenstore/";
    private $total_balance = 0;

    public function testTotalBalance()
    {

        $faker = Faker\Factory::create();
        $httpPostNewPayment = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(6),
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
        );

        for($i = 0; $i <= 30; $i++) {

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

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray['data'], true));
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

    public function testPaymentLessThanBalance()
    {

        $faker = Faker\Factory::create();
        $httpCall = new HttpCall($this->url . "payment");
        // Define the request body
        $data = array(
            'date_payment' => $faker->date('Y-m-d'),
            'id_payment' => $faker->text(30),
            'id_order' => $faker->text(6),
            'balance' => $faker->numberBetween(10000, 100000),
            'is_paid' => false,
        );

        // reset total balance
        $this->total_balance = 0;

        $httpCall->setData($data);
        $httpCall->addJWTToken();
        
        for($i = 0; $i <= 30; $i++) {

            $this->total_balance += $data['balance'];

            $response = $httpCall->getResponse("POST");
    
            $convertToAssocArray = json_decode($response, true);
    
            // fwrite(STDERR, print_r($response, true));
            // Verify that the response same as expected
            $this->assertArrayHasKey('success', $convertToAssocArray);
            $this->assertArrayHasKey('id', $convertToAssocArray, $response);
            $this->assertEquals(true, $convertToAssocArray['success']);
        }

        // get all payments
        // payment by id order
        $id_order = $data['id_order'];
        $httpCall->setNewURL($this->url .'payments?id_order=' .$id_order);
        // Send a GET request to the /endpoint URL
        $response = $httpCall->getResponse("GET");

        // $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));

        $httpCall->setNewURL("$this->url . 'payment_mark_as_paid'");
        // // mark payment as paid with balance decrement by 30
        foreach ($convertToAssocArray['data'] as $payment) {
            $balance_to_update = $payment['balance'] - 30;

            $data_to_send = array(
                'balance' => $balance_to_update,
                'date_payment' => $faker->date('Y-m-d'),
                'id_payment' => $payment['id']
            );

            $httpCall->setData($data_to_send);

            $httpCall->getResponse("PUT");
        }
        
        // check next balance must be balance + 30
        // $httpCall->setNewURL($this->url .'payments?id_order=' .$id_order);
        // // Send a GET request to the /endpoint URL
        // $response = $httpCall->getResponse("GET");

        // $convertToAssocArray = json_decode($response, true);
        
        // compare total balance

        // $total_balance_to_check = 0;
        // foreach ($convertToAssocArray['data'] as $payment) {

        //     $total_balance_to_check += $payment['balance'];
        // }

        // $this->assertEquals($this->total_balance, $total_balance_to_check);

    }
}
