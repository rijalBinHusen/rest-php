<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/user_test.php');

use PHPUnit\Framework\TestCase;

class Order_test extends TestCase
{
    private $url = "binhusenstore/";
    private $url_host_id = null;
    private  $data_posted = null;

    public function testPostEndpoint()
    {
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
        // $this->assertEquals(201, http_response_code());
        $this->url_host_id = $this->url . "order/" . $convertToAssocArray['id'];
    }

    public function testPostEndpointFailed400()
    {
        $httpCallVar = new HttpCall($this->url . 'order');
        // Define the request body
        $data = array(
            'date_order_' => 'Failed test',
            'id_group' => 'Failed test',
            'is_group' => 'Failed test',
            'id_product' => 'Failed test',
            'name_of_customer' => 'Failed test',
            'sent' => 'false',
            'title' => 'Failed test',
            'total_balance' => 'Failed test'
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add order, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'order');
        // Define the request body

        $data = array('title' => "Failed test");

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

        $http = new HttpCall($this->url . 'orders');

        $user = new User_test();
        $user->LoginAdmin();


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
        $this->assertArrayHasKey('date_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_product', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('name_of_customer', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('title', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_balance', $convertToAssocArray['data'][0]);
    }

    public function testGetEndpointFailed401()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url . 'orders');
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testGetByIdEndpoint()
    {
        $this->testPostEndpoint();

        $http = new HttpCall($this->url_host_id);
        // Send a GET request to the /endpoint URL

        $http->addAccessCode("binhusenstore-access-code.txt");

        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('date_order', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('is_group', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('id_product', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('name_of_customer', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('sent', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('title', $convertToAssocArray['data'][0]);
        $this->assertArrayHasKey('total_balance', $convertToAssocArray['data'][0]);
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
        $http = new HttpCall($this->url . 'order/SDFLSKDFJ');

        $http->addAccessCode("binhusenstore-access-code.txt");;

        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Order not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint201()
    {
        $this->testPostEndpoint();

        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('title' => "Updated");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Update order success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed400()
    {
        $this->testPostEndpoint();

        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('date_order__' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to update order, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed401()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');
        // Define the request body
        $data = array('date_order' => "Failed test");

        $httpCallVar->setData($data);

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed404()
    {
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');
        // Define the request body
        $data = array('date_order' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Order not found", $convertToAssocArray['message']);
    }

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
        $this->assertEquals("Delete order success", $convertToAssocArray['message']);
    }

    public function testDeleteEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');

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
        $httpCallVar = new HttpCall($this->url . 'order/loremipsum');

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("DELETE");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Order not found", $convertToAssocArray['message']);
    }

    // 2 order has no id_group
    public function testMerge2OrderIn1IdGroup()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "order");

        $user = new User_test();
        $user->LoginAdmin();

        // create order 1
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => "",
            'is_group' => false,
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => false,
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();
        $response_order1 = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response_order1, true);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $id_order_id1 = $convertToAssocArray['id'];

        // create order 2
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => "",
            'is_group' => false,
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => false,
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();
        $response_order2 = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response_order2, true);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $id_order_id2 = $convertToAssocArray['id'];

        // request to update id_group
        $httpToUpdate = new HttpCall($this->url . "order/merge_as_group");
        $data_to_sent = array(
            "id_order_1" => $id_order_id1,
            "id_order_2" => $id_order_id2,
        );

        $httpToUpdate->setData($data_to_sent);
        $httpToUpdate->addJWTToken();
        $response_update = $httpToUpdate->getResponse("PUT");
        $convertToAssocArray = json_decode($response_update, true);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Order grouped", $convertToAssocArray['message']);

        // get the both order
        $http_get_order_1 = new HttpCall($this->url . "order/" . $id_order_id1);
        $http_get_order_1->addAccessCode("binhusenstore-access-code.txt");

        $response_get_order_1 = $http_get_order_1->getResponse("GET");
        $response_get_order_1_as_array = json_decode($response_get_order_1, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $response_get_order_1_as_array);
        $this->assertEquals(true, $response_get_order_1_as_array['success']);

        $http_get_order_2 = new HttpCall($this->url . "order/" . $id_order_id2);
        $http_get_order_2->addAccessCode("binhusenstore-access-code.txt");

        $response_get_order_2 = $http_get_order_2->getResponse("GET");
        $response_get_order_2_as_array = json_decode($response_get_order_2, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $response_get_order_2_as_array);
        $this->assertEquals(true, $response_get_order_2_as_array['success']);

        // make sure id_group must be same
        $is_id_group_same = $response_get_order_1_as_array['id_group'] === $response_get_order_2_as_array['id_group'];
        $this->assertEquals(true, $is_id_group_same);
    }

    // order 1 has id_group
    public function test_add_id_group_to_id_order2()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "order");

        $user = new User_test();
        $user->LoginAdmin();

        // create order 1
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => $faker->text(9),
            'is_group' => true,
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => false,
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();
        $response_order1 = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response_order1, true);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $id_order_id1 = $convertToAssocArray['id'];

        // create order 2
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => "",
            'is_group' => false,
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => false,
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();
        $response_order2 = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response_order2, true);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $id_order_id2 = $convertToAssocArray['id'];

        // request to update id_group
        $httpToUpdate = new HttpCall($this->url . "order/merge_as_group");
        $data_to_sent = array(
            "id_order_1" => $id_order_id1,
            "id_order_2" => $id_order_id2,
        );

        $httpToUpdate->setData($data_to_sent);
        $httpToUpdate->addJWTToken();
        $response_update = $httpToUpdate->getResponse("PUT");
        $convertToAssocArray = json_decode($response_update, true);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Order grouped", $convertToAssocArray['message']);

        // get the both order
        $http_get_order_1 = new HttpCall($this->url . "order/" . $id_order_id1);
        $http_get_order_1->addAccessCode("binhusenstore-access-code.txt");

        $response_get_order_1 = $http_get_order_1->getResponse("GET");
        $response_get_order_1_as_array = json_decode($response_get_order_1, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $response_get_order_1_as_array);
        $this->assertEquals(true, $response_get_order_1_as_array['success']);

        $http_get_order_2 = new HttpCall($this->url . "order/" . $id_order_id2);
        $http_get_order_2->addAccessCode("binhusenstore-access-code.txt");

        $response_get_order_2 = $http_get_order_2->getResponse("GET");
        $response_get_order_2_as_array = json_decode($response_get_order_2, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $response_get_order_2_as_array);
        $this->assertEquals(true, $response_get_order_2_as_array['success']);

        // make sure id_group must be same
        $is_id_group_same = $response_get_order_1_as_array['id_group'] === $response_get_order_2_as_array['id_group'];
        $this->assertEquals(true, $is_id_group_same);
    }

    // order 2 has id_group
    public function test_add_id_group_to_id_order1()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "order");

        $user = new User_test();
        $user->LoginAdmin();

        // create order 1
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => "",
            'is_group' => false,
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => false,
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();
        $response_order1 = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response_order1, true);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $id_order_id1 = $convertToAssocArray['id'];

        // create order 2
        // Define the request body
        $data = array(
            'date_order' => $faker->date('Y-m-d'),
            'id_group' => $faker->text(9),
            'is_group' => true,
            'id_product' => $faker->text(9),
            'name_of_customer' => $faker->text(40),
            'sent' => false,
            'title' => $faker->text(47),
            'total_balance' => $faker->numberBetween(100000, 1000000),
            'phone' => $faker->numberBetween(100000000000, 999999999999),
            'admin_charge' => true
        );

        $http->setData($data);
        $http->addJWTToken();
        $response_order2 = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response_order2, true);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $id_order_id2 = $convertToAssocArray['id'];

        // request to update id_group
        $httpToUpdate = new HttpCall($this->url . "order/merge_as_group");
        $data_to_sent = array(
            "id_order_1" => $id_order_id1,
            "id_order_2" => $id_order_id2,
        );

        $httpToUpdate->setData($data_to_sent);
        $httpToUpdate->addJWTToken();
        $response_update = $httpToUpdate->getResponse("PUT");
        $convertToAssocArray = json_decode($response_update, true);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Order grouped", $convertToAssocArray['message']);

        // get the both order
        $http_get_order_1 = new HttpCall($this->url . "order/" . $id_order_id1);
        $http_get_order_1->addAccessCode("binhusenstore-access-code.txt");

        $response_get_order_1 = $http_get_order_1->getResponse("GET");
        $response_get_order_1_as_array = json_decode($response_get_order_1, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $response_get_order_1_as_array);
        $this->assertEquals(true, $response_get_order_1_as_array['success']);

        $http_get_order_2 = new HttpCall($this->url . "order/" . $id_order_id2);
        $http_get_order_2->addAccessCode("binhusenstore-access-code.txt");

        $response_get_order_2 = $http_get_order_2->getResponse("GET");
        $response_get_order_2_as_array = json_decode($response_get_order_2, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $response_get_order_2_as_array);
        $this->assertEquals(true, $response_get_order_2_as_array['success']);

        // make sure id_group must be same
        $is_id_group_same = $response_get_order_1_as_array['id_group'] === $response_get_order_2_as_array['id_group'];
        $this->assertEquals(true, $is_id_group_same);
    }
    // error 400 bad request
    public function test_merge_order_404()
    {
        $user = new User_test();
        $user->LoginAdmin();

        $httpToUpdate = new HttpCall($this->url . "order/merge_as_group");

        $data_to_sent = array(
            "id_order_1" => "failed",
            "id_order_2" => "failed2",
        );

        $httpToUpdate->setData($data_to_sent);
        $httpToUpdate->addJWTToken();
        $response_update = $httpToUpdate->getResponse("PUT");
        $convertToAssocArray = json_decode($response_update, true);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals("Failed to merge order, check the data you sent", $convertToAssocArray['message']);
    }
    // error 401 Auth failed
    // error 404 not found
}
