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
        fwrite(STDERR, print_r($convertToAssocArray['data'], true));
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
}
