<?php

class HttpCall {
    private $url;
    private $data_string;
    private $headers;

    public function __construct($url)
    {
        $this->url = $url;
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
    
    public function getResponse($operation) {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $operation);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    public function addHeaders($key, $value) {
        $this->headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($this->data_string),
            $key . ": " . $value
        );
    }
}