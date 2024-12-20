<?php
require_once(__DIR__ . '/google_sign_model.php');

class Google_sign_controller
{
    protected $Google_sign_model;

    public function generate_auth_url()
    {
        $req = Flight::request();
        $url_to_application = $req->query->url_app;

        if (is_null($url_to_application)) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid"
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model();
        $auth_url = $google_sign_in->getAuthURL();

        setcookie("url_to_application", $url_to_application);
        echo "<a href='" . $auth_url . "'>Google login</a>";
        // Flight::redirect($auth_url);
    }

    public function redirect_to_origin_url()
    {
        $req = Flight::request();
        $access_code = $req->query->code;

        $url_to_application = $this->get_cookie_on_request("url_to_application");
        $encoded_url = urldecode($url_to_application) . "?code=" . $access_code;
        setcookie("url_to_application", $url_to_application, time() - 3600); // remove cookie
        Flight::redirect($encoded_url);
    }

    public function getAccessToken()
    {
        $req = Flight::request();
        $access_code = $req->query->code;

        if (is_null($access_code)) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid"
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model();
        $token = $google_sign_in->getAccessTokenByCode($access_code);

        if ($token == false) {

            Flight::json(array(
                "success" => false,
                "message" => "Access code invalid"
            ), 400);
            return;
        }

        // setcookie('my_cookie', 'value', time() + 3600, '/', NULL, true, true);
        setcookie('Google-access-token', $token, time() + ((3600 * 24) * 3), '/', NULL, true, true);

        Flight::json(array(
            "success" => true,
            "token" => "Token setted"
        ), 200);
    }

    public function getUserInfo()
    {
        $access_token = $this->get_cookie_on_request("Google-access-token");

        if (is_null($access_token) || $access_token == false) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid",
                "token" => $access_token
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model();
        $data = $google_sign_in->getUserInfoByAccessToken($access_token);

        if (!$data) {

            Flight::json(array(
                "success" => false,
                "message" => "Access token invalid"
            ), 400);
            return;
        }

        // generate jwt token
        $token = $google_sign_in->generate_jwt_token($data['email']);

        setcookie('JWT-Authorization', $token, time() + ((3600 * 24) * 3), '/', NULL, true, true);
        Flight::json(array(
            "success" => true,
            "data" => $data,
            Flight::response()->cache(time() + (60 * 60 * 24)) // cache for 24hours
        ), 200);
    }

    public function sign_out()
    {
        $access_token = $this->get_cookie_on_request("Google-access-token");

        if (is_null($access_token) || $access_token == false) {

            Flight::json(array(
                "success" => false,
                "message" => "Request invalid"
            ), 400);
            return;
        }

        $google_sign_in = new Google_sign_model();
        $data = $google_sign_in->signOut($access_token);

        if (!$data) {

            Flight::json(array(
                "success" => false,
                "message" => "Access token invalid"
            ), 400);
            return;
        }

        Flight::json(array(
            "success" => true,
            "data" => $data,
        ), 200);
    }


    protected function get_cookie_on_request($cookie_name)
    {

        $is_cookie_set =  array_key_exists($cookie_name, $_COOKIE) && strlen($_COOKIE[$cookie_name]) > 0;

        $is_token_set = $is_cookie_set;

        if ($is_token_set) {

            return $_COOKIE[$cookie_name];
        } else {

            return false;
        }
    }
}
