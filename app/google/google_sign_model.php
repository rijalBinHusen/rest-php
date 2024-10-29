<?php

require_once(__DIR__ . '/../../utils/database.php');

class Google_sign_modal
{
    protected $client;

    function __construct()
    {
        // init configuration
        $clientID = GOOGLE_CLIENT_ID;
        $clientSecret = GOOGLE_CLIENT_SECRET;
        $redirectUri = REDIRECT_URI;

        $this->client = new Google_Client();
        $this->client->setClientId($clientID);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    function getAuthURL()
    {
        return $this->client->createAuthUrl();
    }

    function getUserInfoByCode($code)
    {

        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        // check is it token exists or not
        if (isset($token['access_token'])) $this->client->setAccessToken($token['access_token']);
        else return false;

        // get profile info
        $google_oauth = new Google_Service_Oauth2($this->client);
        $google_account_info = $google_oauth->userinfo->get();
        $email =  $google_account_info->email;
        $name =  $google_account_info->name;
        echo "<a href='" . $client->revokeToken() . "'>Google Logout</a>";
    }
}

// create Client Request to access Google API

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {

    // now you can use this profile info to create account in your website and make user logged in.
}
