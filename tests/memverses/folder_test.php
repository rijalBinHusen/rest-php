<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class folder_test extends TestCase
{
    private $url = "memverses/";
    private $url_host_id = null;
    private $data_posted = null;

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "folder");
        // Define the request body
        $data = array(
            "name" => $faker->text(80),
            "total_verse_to_show" => $faker->numberBetween(1, 10),
            "show_next_chapter_on_second" => $faker->numberBetween(1, 1000000),
            "read_target" => $faker->numberBetween(1, 75),
            "is_show_first_letter" => $faker->boolean(),
            "is_show_tafseer" => $faker->boolean(),
            "arabic_size" => $faker->numberBetween(1, 75),
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->url_host_id = $this->url . "folder/" . $convertToAssocArray['id'];
    }

    public function testPostEndpointFailed400()
    {
        $httpCallVar = new HttpCall($this->url . 'folder');
        // Define the request body
        $data = array(
            "name" => "false",
            "total_verse_to_show" => "false",
            "show_next_chapter_on_second" => "false",
            "read_target" => "false",
            "is_show_first_letter" => "false",
            "is_show_tafseer" => "false",
            "arabic_size" => "false",
        );

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to add folder, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPostEndpointFailed401()
    {
        $httpCallVar = new HttpCall($this->url . 'folder');
        // Define the request body

        $data = array('id_user' => "Failed test");

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

        $http = new HttpCall($this->url . 'folders');
        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);

        $data_posted = $this->data_posted;
        $this->assertArrayHasKey($data_posted['id_user'], $convertToAssocArray['data'][0]['id_user']);
        $this->assertArrayHasKey($data_posted['name'], $convertToAssocArray['data'][0]['name']);
        $this->assertArrayHasKey($data_posted['total_verse_to_show'], $convertToAssocArray['data'][0]['total_verse_to_show']);
        $this->assertArrayHasKey($data_posted['show_next_chapter_on_second'], $convertToAssocArray['data'][0]['show_next_chapter_on_second']);
        $this->assertArrayHasKey($data_posted['read_target'], $convertToAssocArray['data'][0]['read_target']);
        $this->assertArrayHasKey($data_posted['is_show_first_letter'], $convertToAssocArray['data'][0]['is_show_first_letter']);
        $this->assertArrayHasKey($data_posted['is_show_tafseer'], $convertToAssocArray['data'][0]['is_show_tafseer']);
        $this->assertArrayHasKey($data_posted['arabic_size'], $convertToAssocArray['data'][0]['arabic_size']);
        $this->assertArrayHasKey($data_posted['changed_by'], $convertToAssocArray['data'][0]);
    }

    // get folder error 404, 401
    public function testGetEndpointFailed401()
    {

        $http = new HttpCall($this->url . 'folders');
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

        $http->addJWTToken();
        // Send a GET request to the /endpoint URL
        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($this->url_host_id, true));
        // Verify that the response same as expected
        $data_posted = $this->data_posted;
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertArrayHasKey('data', $convertToAssocArray);
        $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);

        $this->assertArrayHasKey($data_posted['id_user'], $convertToAssocArray['data'][0]['id_user']);
        $this->assertArrayHasKey($data_posted['name'], $convertToAssocArray['data'][0]['name']);
        $this->assertArrayHasKey($data_posted['total_verse_to_show'], $convertToAssocArray['data'][0]['total_verse_to_show']);
        $this->assertArrayHasKey($data_posted['show_next_chapter_on_second'], $convertToAssocArray['data'][0]['show_next_chapter_on_second']);
        $this->assertArrayHasKey($data_posted['read_target'], $convertToAssocArray['data'][0]['read_target']);
        $this->assertArrayHasKey($data_posted['is_show_first_letter'], $convertToAssocArray['data'][0]['is_show_first_letter']);
        $this->assertArrayHasKey($data_posted['is_show_tafseer'], $convertToAssocArray['data'][0]['is_show_tafseer']);
        $this->assertArrayHasKey($data_posted['arabic_size'], $convertToAssocArray['data'][0]['arabic_size']);
        $this->assertArrayHasKey($data_posted['changed_by'], $convertToAssocArray['data'][0]);
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
        $http = new HttpCall($this->url . 'folder/SDFLSKDFJ');

        $http->addJWTToken();

        $response = $http->getResponse("GET");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("folder not found", $convertToAssocArray['message']);
    }

    public function testPutEndpoint201()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('name' => "Updated via unit testing");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, true));

        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("Update folder success", $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed400()
    {
        $this->testPostEndpoint();

        $httpCallVar = new HttpCall($this->url_host_id);
        // Define the request body
        $data = array('nobody' => "Failed test");

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        // fwrite(STDERR, print_r($response, true));
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Failed to update folder, check the data you sent', $convertToAssocArray['message']);
    }

    public function testPutEndpointFailed401()
    {
        $this->testPostEndpoint();
        $httpCallVar = new HttpCall($this->url . 'folder/loremipsum');
        // Define the request body
        $data = array('name' => "Failed test");

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
        $httpCallVar = new HttpCall($this->url . 'folder/loremipsum');
        // Define the request body
        $data = array('read_target' => 9);

        $httpCallVar->setData($data);

        $httpCallVar->addJWTToken();

        $response = $httpCallVar->getResponse("PUT");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals("folder not found", $convertToAssocArray['message']);
    }

    // public function testDeleteEndpoint201()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url_host_id);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     // fwrite(STDERR, print_r($response, true));
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Delete folder success", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed401()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'folder/loremipsum');

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testDeleteEndpointFailed404()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'folder/loremipsum');

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("DELETE");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("folder not found", $convertToAssocArray['message']);
    // }
}
