<?php

require_once(__DIR__ . '/../httpCall.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class chapter_test extends TestCase
{
    private $url = "memverses/";
    private $url_host_id = null;
    private $data_posted = null;

    public function testPostChapterAndVersesEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "chapter_and_verses");
        // Define the request body
        $data = array(
            "id_folder" => $faker->numberBetween(1, 75) . "",
            "chapter" => $faker->numberBetween(1, 1000000),
            "verse_start" => $faker->numberBetween(1, 75),
            "verse_end" => $faker->numberBetween(1, 75),
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray, $response);
        $this->assertEquals(true, $convertToAssocArray['success']);
    }

    public function testPostEndpoint()
    {
        $faker = Faker\Factory::create();
        $http = new HttpCall($this->url . "chapter");
        // Define the request body
        $data = array(
            "id_chapter_client" => $faker->text(7),
            "id_folder" => $faker->numberBetween(1, 75) . "",
            "chapter" => $faker->numberBetween(1, 1000000),
            "verse" => $faker->numberBetween(1, 75),
            "readed_times" => $faker->numberBetween(1, 75),
        );

        $this->data_posted = $data;

        $http->setData($data);
        $http->addJWTToken();

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);

        // fwrite(STDERR, print_r($response, true));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertArrayHasKey('id', $convertToAssocArray, $response);
        $this->url_host_id = $this->url . "chapter/" . $convertToAssocArray['id'];
    }

    // public function testPostEndpointFailed400()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'chapter');
    //     // Define the request body
    //     $data = array(
    //         "id_chapter_client" => "false",
    //         "id_folder" => "false",
    //         "chapter" => "false",
    //         "verse" => "false",
    //         "readed_times" => "false",
    //     );

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("POST");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('Failed to add chapter, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPostEndpointFailed401()
    // {
    //     $httpCallVar = new HttpCall($this->url . 'chapter');
    //     // Define the request body

    //     $data = array(
    //         "id_chapter_client" => "false",
    //         "id_folder" => "false",
    //         "chapter" => 1,
    //         "verse" => 2,
    //         "readed_times" => 0,
    //     );

    //     $httpCallVar->setData($data);

    //     $response = $httpCallVar->getResponse("POST");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('You must be authenticated to access this resource.', $convertToAssocArray['message']);
    // }

    // public function testGetEndpoint()
    // {
    //     $this->testPostEndpoint();
    //     $data_posted = $this->data_posted;

    //     $http = new HttpCall($this->url . 'chapters/' . $data_posted['id_folder']);
    //     $http->addJWTToken();
    //     // Send a GET request to the /endpoint URL
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($response, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $last_data_posted = $this->data_posted;
    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data'][0]);
    //     $this->assertEquals($data_posted['id_chapter_client'], $last_data_posted['id_chapter_client']);
    //     $this->assertEquals($data_posted['id_folder'], $last_data_posted['id_folder']);
    //     $this->assertEquals($data_posted['chapter'], $last_data_posted['chapter']);
    //     $this->assertEquals($data_posted['verse'], $last_data_posted['verse']);
    //     $this->assertEquals($data_posted['readed_times'], $last_data_posted['readed_times']);
    // }

    // // // get folder error 404, 401
    // public function testGetEndpointFailed401()
    // {

    //     $http = new HttpCall($this->url . 'chapters/1231231231');
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testGetByIdEndpoint()
    // {
    //     $this->testPostEndpoint();

    //     $http = new HttpCall($this->url_host_id);

    //     $http->addJWTToken();
    //     // Send a GET request to the /endpoint URL
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($this->url_host_id, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $data_posted = $this->data_posted;
    //     $last_data_posted = $convertToAssocArray['data'];

    //     $this->assertArrayHasKey('data', $convertToAssocArray);
    //     $this->assertArrayHasKey('id', $convertToAssocArray['data']);
    //     $this->assertEquals($data_posted['id_chapter_client'], $last_data_posted['id_chapter_client']);
    //     $this->assertEquals($data_posted['id_folder'], $last_data_posted['id_folder']);
    //     $this->assertEquals($data_posted['chapter'], $last_data_posted['chapter']);
    //     $this->assertEquals($data_posted['verse'], $last_data_posted['verse']);
    //     $this->assertEquals($data_posted['readed_times'], $last_data_posted['readed_times']);
    // }

    // public function testGetByIdEndpointFailed401()
    // {
    //     $this->testPostEndpoint();

    //     $http = new HttpCall($this->url_host_id);
    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // }

    // public function testGetByIdEndpointFailed404()
    // {
    //     $this->testPostEndpoint();
    //     $http = new HttpCall($this->url . 'chapter/SDFLSKDFJ');

    //     $http->addJWTToken();

    //     $response = $http->getResponse("GET");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($convertToAssocArray, true));
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Chapter not found", $convertToAssocArray['message']);
    // }

    // public function testPutEndpoint201()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array('readed_times' => 70);

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // fwrite(STDERR, print_r($response, true));

    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(true, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Update chapter success", $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed400()
    // {
    //     $this->testPostEndpoint();

    //     $httpCallVar = new HttpCall($this->url_host_id);
    //     // Define the request body
    //     $data = array('nobody' => "Failed test");

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     // fwrite(STDERR, print_r($response, true));
    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals('Failed to update chapter, check the data you sent', $convertToAssocArray['message']);
    // }

    // public function testPutEndpointFailed401()
    // {
    //     $this->testPostEndpoint();
    //     $httpCallVar = new HttpCall($this->url . 'chapter/loremipsum');
    //     // Define the request body
    //     $data = array('readed_times' => 9);

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
    //     $httpCallVar = new HttpCall($this->url . 'chapter/loremipsum');
    //     // Define the request body
    //     $data = array('readed_times' => 9);

    //     $httpCallVar->setData($data);

    //     $httpCallVar->addJWTToken();

    //     $response = $httpCallVar->getResponse("PUT");

    //     $convertToAssocArray = json_decode($response, true);
    //     // Verify that the response same as expected
    //     $this->assertArrayHasKey('success', $convertToAssocArray);
    //     $this->assertEquals(false, $convertToAssocArray['success']);

    //     $this->assertArrayHasKey('message', $convertToAssocArray);
    //     $this->assertEquals("Chapter not found", $convertToAssocArray['message']);
    // }

    // // public function testDeleteEndpoint201()
    // // {
    // //     $this->testPostEndpoint();
    // //     $httpCallVar = new HttpCall($this->url_host_id);

    // //     $httpCallVar->addJWTToken();

    // //     $response = $httpCallVar->getResponse("DELETE");

    // //     $convertToAssocArray = json_decode($response, true);
    // //     // Verify that the response same as expected
    // //     // fwrite(STDERR, print_r($response, true));
    // //     $this->assertArrayHasKey('success', $convertToAssocArray);
    // //     $this->assertEquals(true, $convertToAssocArray['success']);

    // //     $this->assertArrayHasKey('message', $convertToAssocArray);
    // //     $this->assertEquals("Delete folder success", $convertToAssocArray['message']);
    // // }

    // // public function testDeleteEndpointFailed401()
    // // {
    // //     $httpCallVar = new HttpCall($this->url . 'chapter/loremipsum');

    // //     $response = $httpCallVar->getResponse("DELETE");

    // //     $convertToAssocArray = json_decode($response, true);
    // //     // Verify that the response same as expected
    // //     $this->assertArrayHasKey('success', $convertToAssocArray);
    // //     $this->assertEquals(false, $convertToAssocArray['success']);

    // //     $this->assertArrayHasKey('message', $convertToAssocArray);
    // //     $this->assertEquals("You must be authenticated to access this resource.", $convertToAssocArray['message']);
    // // }

    // // public function testDeleteEndpointFailed404()
    // // {
    // //     $httpCallVar = new HttpCall($this->url . 'chapter/loremipsum');

    // //     $httpCallVar->addJWTToken();

    // //     $response = $httpCallVar->getResponse("DELETE");

    // //     $convertToAssocArray = json_decode($response, true);
    // //     // Verify that the response same as expected
    // //     $this->assertArrayHasKey('success', $convertToAssocArray);
    // //     $this->assertEquals(false, $convertToAssocArray['success']);

    // //     $this->assertArrayHasKey('message', $convertToAssocArray);
    // //     $this->assertEquals("folder not found", $convertToAssocArray['message']);
    // // }
}
