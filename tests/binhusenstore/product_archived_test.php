<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/user_test.php');

use PHPUnit\Framework\TestCase;

class Product_archived_test extends TestCase
{
    private $url = "binhusenstore/";
    private $id_posted = null;
    private $data_posted = null;

    private function generateNewProductData()
    {
        $faker = Faker\Factory::create();

        $data = array(
            'name' => $faker->city(),
            'categories' => $faker->text(30),
            'price' => $faker->numberBetween(10000, 100000),
            'weight' => $faker->numberBetween(100, 1000),
            'images' => $faker->imageUrl(640, 480),
            'description' => $faker->text(150),
            'default_total_week' => $faker->numberBetween(20, 60),
            'is_available' => $faker->boolean(),
            'is_admin_charge' => $faker->boolean()
        );

        $data_posted = $data;

        return $data;
    }

    private function loginToAdmin()
    {
        $user = new User_test();
        $user->LoginAdmin();
    }

    // create new product
    public function testPostEndpoint()
    {
        $http = new HttpCall($this->url . "product");
        // Define the request body
        $generatedProductData = $this->generateNewProductData();
        $http->setData($generatedProductData);

        $this->loginToAdmin();
        $http->addJWTToken();
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r( PHP_EOL . $response . PHP_EOL, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        // $this->assertEquals(201, http_response_code());
        $this->id_posted = $convertToAssocArray['id'];
    }
    // move product to archive
    public function testMoveProductToArchive()
    {
        $this->testPostEndpoint();
        $http = new HttpCall($this->url . "product/move_to_archive");
        $http->setData(array('id' => $this->id_posted));

        $this->loginToAdmin();
        $http->addJWTToken();
        $response = $http->getResponse("POST");
        $convertToAssocArray = json_decode($response, true);

        $this->assertArrayHasKey('success', $convertToAssocArray, $response);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success'], $response);
        $this->assertEquals("Product archived", $convertToAssocArray['message'], $response);
    }

    // // bring back product
    // public function testBringBackProduct()
    // {
    //     $this->testMoveProductToArchive();
    //     $http = new HttpCall($this->url . "product_archived/" . $this->id_posted);

    //     $this->loginToAdmin();
    //     $http->addJWTToken();

    //     $response = $http->getResponse("PUT");
    //     $convertToAssocArray = json_decode($response, true);

    //     // fwrite(STDERR, print_r(PHP_EOL . $this->id_posted . PHP_EOL, true));
    //     $this->assertArrayHasKey('success', $convertToAssocArray, $response);
    //     $this->assertArrayHasKey('message', $convertToAssocArray, $response);
    //     $this->assertEquals(true, $convertToAssocArray['success'], $response);
    //     $this->assertEquals("Product unarchived", $convertToAssocArray['message'], $response);

    //     //================= product should be not exists on archive
    //     $http_get_product_archived = new HttpCall($this->url . 'product_archived/' . $this->id_posted);
    //     $this->loginToAdmin();
    //     $http_get_product_archived->addJWTToken();

    //     $response = $http_get_product_archived->getResponse("PUT");
    //     $convertToAssocArray = json_decode($response, true);

    //     // fwrite(STDERR, print_r(PHP_EOL . $this->id_posted . PHP_EOL, true));
    //     $this->assertArrayHasKey('success', $convertToAssocArray, $response);
    //     $this->assertArrayHasKey('message', $convertToAssocArray, $response);
    //     $this->assertEquals(false, $convertToAssocArray['success'], $response);
    //     $this->assertEquals("Product not found", $convertToAssocArray['message'], $response);

    //     //=================== product should be exists on product end point
    //     $http_get_product = new HttpCall($this->url . 'product/' . $this->id_posted);
    //     $http_get_product->addAccessCode("binhusenstore-access-code.txt");
    //     // Send a GET request to the /endpoint URL
    //     $response = $http_get_product->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $data_response = $convertToAssocArray['data'][0];
    //     $data_posted = $this->data_posted;

    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);

    //     $this->assertEquals($data_posted['name'], $data_response['name']);
    //     $this->assertEquals($data_posted['categories'], $data_response['categories']);
    //     $this->assertEquals($data_posted['price'], $data_response['price']);
    //     $this->assertEquals($data_posted['weight'], $data_response['weight']);
    //     $this->assertEquals($data_posted['images'], $data_response['images']);
    //     $this->assertEquals($data_posted['description'], $data_response['description']);
    //     $this->assertEquals($data_posted['default_total_week'], $data_response['default_total_week']);
    //     $this->assertEquals($data_posted['is_available'], $data_response['is_available']);
    // }

    // // remove product
    public function testRemoveProduct()
    {
        $this->testMoveProductToArchive();
        $http = new HttpCall($this->url . "product_archived/" . $this->id_posted);

        $this->loginToAdmin();
        $http->addJWTToken();

        $response = $http->getResponse("DELETE");
        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r(PHP_EOL . $this->id_posted . PHP_EOL, true));
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Product removed", $convertToAssocArray['message']);

        //================= product should be not exists on archive
        // $http_get_product_archived = new HttpCall($this->url . 'product_archived/' . $this->id_posted);
        // $this->loginToAdmin();
        // $http_get_product_archived->addJWTToken();

        // $response = $http_get_product_archived->getResponse("PUT");
        // $convertToAssocArray = json_decode($response, true);

        // // fwrite(STDERR, print_r(PHP_EOL . $this->id_posted . PHP_EOL, true));
        // $this->assertArrayHasKey('success', $convertToAssocArray, $response);
        // $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        // $this->assertEquals(false, $convertToAssocArray['success'], $response);
        // $this->assertEquals("Product not found", $convertToAssocArray['message'], $response);

        //=================== product should be not exists on product end point
        $http_get_product = new HttpCall($this->url . 'product/' . $this->id_posted);
        $http_get_product->addAccessCode("binhusenstore-access-code.txt");
        // Send a GET request to the /endpoint URL
        $response = $http_get_product->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Product not found", $convertToAssocArray['message']);
    }
}
