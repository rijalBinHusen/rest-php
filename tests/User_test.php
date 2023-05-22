<?php

require_once(__DIR__ ."/../tests/httpCall.php");
require_once(__DIR__ . '/../vendor/fakerphp/faker/src/autoload.php');

class MyRestServerUserTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/user/";
    // Test register email exists
    public function testRegisterEndpointFailed()
    {
        $http = new HttpCall($this->url . "register");
        // Define the request body
        $data = array(
            'email' => 'test@test.com', 
            'password' => "1233333", 
            'name' => "name0123"
        );

        $http->setData($data);

        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // fwrite(STDERR, print_r($convertToAssocArray, TRUE));
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
    // Test register must success
    public function testRegisterEndpoint()
    {
        $http = new HttpCall($this->url . "register");
        $faker = Faker\Factory::create();
        // Define the request body
        $data = array(
            'email' => $faker->email, 
            'password' => $faker->numberBetween(100000, 1000000), 
            'name' => $faker->name("female")
        );
        $http->setData($data);
        $response = $http->getResponse("POST");

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
        $this->assertEquals(true, $convertToAssocArray['success']);
        $this->assertEquals('Registration success.', $convertToAssocArray['message']);
    }

    // test login success
    public function testLoginEndpoint()
    {
        $http = new HttpCall($this->url . "login");
        // Define the request body
        $data = array('email' => 'test@test.com', 'password' => '12345');
        $http->setData($data);
        $response = $http->getResponse("POST");
        
        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('token', $convertToAssocArray);
        // fwrite(STDERR, print_r($convertToAssocArray, TRUE));

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
        $data = array('email' => 'test@test.com', 'password' => '1234');
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

}
