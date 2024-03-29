<?php

class Queue
{

    private $cookie_file_name = "cookie_queue.txt";

    public function login()
    {
        $curlLogin = curl_init();

        $user = array("username" => "gudang", "Password" => "123");

        curl_setopt_array($curlLogin, [
            CURLOPT_PORT => "8080",
            CURLOPT_URL => "http://182.16.186.138:8080/antrian2/login/login_process",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($user),
            CURLOPT_HTTPHEADER => [
                "Host: 182.16.186.138:8080",
                "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/117.0",
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: gzip, deflate",
                "Content-Type: application/x-www-form-urlencoded",
                "Content-Length: 28",
                "Origin: http://182.16.186.138:8080",
                "DNT: 1",
                "Connection: keep-alive",
                "Referer: http://182.16.186.138:8080/antrian2/",
                "Upgrade-Insecure-Requests: 1",
            ],
            CURLOPT_HEADER => true,
        ]);

        $response = curl_exec($curlLogin);

        list($headers, $body) = explode("\r\n\r\n", $response, 2);

        // Extract the Set-Cookie header from the response headers
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $matches);
        $cookies = implode('; ', $matches[1]);
        $split_cookies = explode(";", $cookies);

        // write cookie to file
        $myfile = fopen($this->cookie_file_name, "w");
        fwrite($myfile, $split_cookies[1]);
        fclose($myfile);
        return $split_cookies[1];
    }

    public function get_dashboard()
    {

        $cookie_to_use = "";
        $myfile = fopen($this->cookie_file_name, "r");
        if ($myfile) {

            $cookie_to_use = fgets($myfile);
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_PORT => "8080",
            CURLOPT_URL => "http://182.16.186.138:8080/antrian2/antrian/antrido",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Cookie: " . $cookie_to_use,
                "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/117.0"
            ],
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($http_code != 200) $this->login();

        if ($err || $http_code != 200) {
            echo "cURL Error #:" . $err . "http response: " . $http_code . "Coba lagi!";
        } else {
            echo $response;
        }
    }
}
