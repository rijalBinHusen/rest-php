<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/user_test.php');

use PHPUnit\Framework\TestCase;

class Product_archived_test extends TestCase
{
    private $url = "binhusenstore/";
    private $id_posted = null;

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

    // bring back product
    public function testBringBackProduct()
    {
        $this->testMoveProductToArchive();
        $http = new HttpCall($this->url . "product_archived/" . $this->id_posted);

        $this->loginToAdmin();
        $http->addJWTToken();

        $response = $http->getResponse("PUT");
        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r(PHP_EOL . $this->id_posted . PHP_EOL, true));
        $this->assertArrayHasKey('success', $convertToAssocArray, $response);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success'], $response);
        $this->assertEquals("Product unarchived", $convertToAssocArray['message'], $response);
    }

    // remove product
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
    }
}
