<?php

class MyRestServerUserTest extends PHPUnit_Framework_TestCase
{
    private $url = "http://localhost/rest-php/";
    // Test register must failed
    public function testRegisterEndpointFailed()
    {
        $faker = Faker\Factory::create();
        // Define the request body
        $data = array('email' => 'test@test.com', 'password' => $faker->numberBetween(10000, 100000), 'username' => $faker->name());
        $data_string = json_encode($data);
        
        // Set up the request headers
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        );
        
        // Send a POST request to the /endpoint URL with the request body
        $ch = curl_init($this->url . "register");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
    }
    // Test register must success
    
    // test login success
    public function testLoginEndpoint()
    {
        // Define the request body
        $data = array('email' => 'test@test.com', 'password' => '12345');
        $data_string = json_encode($data);
        
        // Set up the request headers
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        );
        
        // Send a POST request to the /endpoint URL with the request body
        $ch = curl_init($this->url . "login");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        $convertToAssocArray = json_decode($response, true);
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
        // Define the request body
        $data = array('email' => 'test@test.com', 'password' => '1234');
        $data_string = json_encode($data);
        
        // Set up the request headers
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        );
        
        // Send a POST request to the /endpoint URL with the request body
        $ch = curl_init($this->url . "login");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        $convertToAssocArray = json_decode($response, true);
        // Verify that the response same as expected
        $this->assertArrayHasKey('success', $convertToAssocArray);
        $this->assertArrayHasKey('message', $convertToAssocArray);
    }
    // test validation must failed

    // test validation must success

}
