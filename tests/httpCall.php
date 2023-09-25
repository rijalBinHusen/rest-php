<?php

class HttpCall {
    private $url;
    private $data_string;
    private $headers;

    public function __construct($url)
    {
        $this->url = $url;
        $this->setHeaders();
    }

    public function getEndPoint() {
        return file_get_contents($this->url);
    }

    public function setData($arrayData) {
        $this->data_string = json_encode($arrayData);
        $this->setHeaders();
    }

    private function setHeaders() {
        $this->headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($this->data_string)
        );
    }
    
    public function getResponse($operation, $uploadImageByPath = null) {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $operation);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($uploadImageByPath !== null) {

            $data = array('name' => 'image', 'file' => '@/' . $uploadImageByPath);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    public function addJWTToken() {
        
        $myfile = fopen("token.txt", "r") or die("Unable to open file!");
        $token = fgets($myfile);
        fclose($myfile);

        $this->headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($this->data_string),
            "JWT-Authorization: " . $token
        );
    }

    public function addHeaders($key, $value) {
        $this->headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($this->data_string),
            $key . ": " . $value
        );
    }
}