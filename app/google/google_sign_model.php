<?php

require_once(__DIR__ . '/../../utils/database.php');

class Google_sign_model
{
    protected $client;

    function __construct()
    {
        // init configuration
        $clientID = GOOGLE_CLIENT_ID;
        $clientSecret = GOOGLE_CLIENT_SECRET;

        $this->client = new Google_Client();
        $this->client->setClientId($clientID);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri("http://localhost:8000/google/redirect_to_application");
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    public function getAuthURL()
    {
        return $this->client->createAuthUrl();
    }

    public function getAccessTokenByCode($code)
    {

        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        // check is it token exists or not
        if (isset($token['access_token'])) return $token['access_token'];
        return false;
    }

    public function getUserInfoByAccessToken($access_token)
    {
        if (is_null($access_token)) return false;

        $this->client->setAccessToken($access_token);
        // get profile info
        $google_oauth = new Google_Service_Oauth2($this->client);
        $google_account_info = $google_oauth->userinfo->get();

        return array(
            "email" =>  $google_account_info->email,
            "name" =>  $google_account_info->name,
            "profile_picture" =>  $google_account_info->picture,
        );
    }

    public function signOut($access_token)
    {
        if (is_null($access_token)) return false;

        $this->client->setAccessToken($access_token);
        $this->client->revokeToken();
    }
}
