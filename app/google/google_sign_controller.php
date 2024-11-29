<?php
require_once(__DIR__ . '/google_sign_model.php');

class Google_sign_controller
{
    protected $Google_sign_model;

    public function generate_auth_url()
    {
        $req = Flight::request();
        $redirect_url = $req->referrer;

        if (is_null($redirect_url)) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid"
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model($redirect_url);
        $auth_url = $google_sign_in->getAuthURL();

        Flight::json(array(
            "success" => false,
            "data" => $auth_url
        ), 200);
    }

    public function getAccessToken()
    {
        $req = Flight::request();
        $access_code = $req->query->access_code;
        $redirect_url = $req->referrer;

        if (is_null($access_code) || is_null($redirect_url)) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid"
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model($redirect_url);
        $token = $google_sign_in->getAccessTokenByCode($access_code);

        if (!$token) {

            Flight::json(array(
                "success" => false,
                "message" => "Access code invalid"
            ), 400);
            return;
        }

        setcookie('Google-access-token', $token, time() + ((3600 * 24) * 7), '/', '', false, true);
        Flight::json(array(
            "success" => false,
            "token" => $token
        ), 200);
    }

    public function getUserInfo()
    {
        $req = Flight::request();
        $access_token = $this->get_access_token_on_request();
        $redirect_url = $req->referrer;

        if (is_null($access_token) || is_null($redirect_url)) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid"
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model($redirect_url);
        $data = $google_sign_in->getUserInfoByAccessToken($access_token);

        if (!$data) {

            Flight::json(array(
                "success" => false,
                "message" => "Access code invalid"
            ), 400);
            return;
        }

        Flight::json(array(
            "success" => false,
            "data" => $data
        ), 200);
    }



    protected function get_access_token_on_request()
    {

        $is_cookie_set =  array_key_exists('Google-access-token', $_COOKIE) && strlen($_COOKIE['Google-access-token']) > 0;
        $is_http_access_set = isset($_SERVER['HTTP_GOOGLE_ACCESS_TOKEN']);

        $is_token_set = $is_cookie_set || $is_http_access_set;

        if ($is_token_set) {

            $access_token = "";

            if ($is_http_access_set) {

                $access_token = $_SERVER['HTTP_GOOGLE_ACCESS_TOKEN'];
            } else {

                $access_token = $_COOKIE['Google-access-token'];;
            }

            return $access_token;
        } else {

            return false;
        }
    }
}
