<?php

// init configuration
$clientID = GOOGLE_CLIENT_ID;
$clientSecret = GOOGLE_CLIENT_SECRET;
$redirectUri = REDIRECT_URI;

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    var_dump($token);
    if (isset($token['access_token'])) $client->setAccessToken($token['access_token']);
    else {
        return;
    }

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;
    echo "<a href='" . $client->revokeToken() . "'>Google Logout</a>";

    // now you can use this profile info to create account in your website and make user logged in.
} else {
    echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";
}
