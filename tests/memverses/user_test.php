<?php

require_once(__DIR__ . "/../../tests/httpCall.php");
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class User_test extends TestCase
{
    private $url = "memverses/user/";
    var $data_inserted = array();
    // Test register must success
    public function testRegisterEndpoint()
    {
        $http = new HttpCall($this->url . "register");
        $faker = Faker\Factory::create();
        // Define the request body
        $email = $faker->email;
        $password = $faker->numberBetween(100000, 1000000);
        $name = $faker->name("female");
        $user_to_post = array(
            'email' => $email,
            'password' => $password,
            'name' => $name
        );

        $this->data_inserted = $user_to_post;

        $http->setData($user_to_post);
        $response = $http->getResponse("POST");

        // fwrite(STDERR, print_r($response, TRUE));
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals('Registration success.', $convertToAssocArray['message']);
    }
    //// Test register email exists
    public function testRegisterEndpointFailed()
    {
        $this->testRegisterEndpoint();
        $http = new HttpCall($this->url . "register");

        $http->setData($this->data_inserted);

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, TRUE));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('User exist.', $convertToAssocArray['message']);
    }

    // Test register user not enter name
    public function testRegisterEndpointWithoutName()
    {
        $http = new HttpCall($this->url . "register");
        // Define the request body
        $data = array(
            'email' => 'test@dfsfsdfsdf.com',
            'password' => "1233333",
        );
        $http->setData($data);
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(false, $convertToAssocArray['success']);
        $this->assertEquals('Unprocessable Entity', $convertToAssocArray['message']);
    }

    // test login success
    public function testLoginEndpoint()
    {
        $this->testRegisterEndpoint();
        $http = new HttpCall($this->url . "login");
        // Define the request body
        $http->setData($this->data_inserted);
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, TRUE));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('token', $convertToAssocArray);

        // save token to a .txt file
        $myfile = fopen("token.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $convertToAssocArray['token']);
        fclose($myfile);
    }

    // test login failed   
    public function testLoginEndpointFailed()
    {
        $http = new HttpCall($this->url . "login");
        // Define the request body
        $data = array('email' => 'memverses_test@test.com', 'password' => '1234');
        $http->setData($data);
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
    }
    // test validation must failed
    public function testValidateEndpointFailed()
    {
        $http = new HttpCall($this->url . "validate");
        // Define the request body
        // get token
        $myfile = fopen("token.txt", "r") or die("Unable to open file!");
        $token = fgets($myfile);
        fclose($myfile);
        // set token on header request
        $http->addHeaders('JWT-Authorization', $token . "Invalidtoken");
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals($convertToAssocArray['success'], false);
        $this->assertEquals($convertToAssocArray['message'], "Invalid token");
    }
    // test validation must success
    public function testValidateEndpoint()
    {
        $http = new HttpCall($this->url . "validate");
        // Define the request body
        // set token on header request
        $http->addJWTToken();
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($token, TRUE));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals("Valid token", $convertToAssocArray['message']);
    }

    // test login success
    public function testUpdateUserPassword()
    {

        $faker = Faker\Factory::create();
        $this->testLoginEndpoint();
        $http = new HttpCall($this->url . "update_password");
        // Define the request body
        $http->setData(
            [
                'password_new' => $faker->numberBetween(100000, 1000000),
                'password_old' => $this->data_inserted['password']
            ]
        );
        $http->addJWTToken();
        $response = $http->getResponse("PUT");

        // fwrite(STDERR, print_r($response . PHP_EOL, TRUE));
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);

        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals('Update password success.', $convertToAssocArray['message']);

        $this->data_inserted['password'] = 00700;
        $this->testLoginEndpoint();
    }

    // test login success
    public function LoginAdmin()
    {
        $http = new HttpCall($this->url . "login");
        // Define the request body
        $http->setData(array(
            'email' => 'mem_test@test.com',
            'password' => '123456'
        ));
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($response, TRUE));
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('token', $convertToAssocArray);

        // save token to a .txt file
        $myfile = fopen("token.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $convertToAssocArray['token']);
        fclose($myfile);
    }
}
