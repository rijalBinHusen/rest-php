<?php

require_once(__DIR__ . '/../../utils/database.php');
require_once(__DIR__ . '/../../app/Users/user_model.php');

class Google_sign_model
{
    protected $client;
    protected $database;

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

        $this->database = Query_builder::getInstance();
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

    public function generate_jwt_token($email)
    {
        $database_table_name = "google_accounts";
        // get users id on db
        $email_info = $this->database->select_from($database_table_name, "id", "", false, 0, "email", $email)->fetchAll(PDO::FETCH_ASSOC);
        $is_email_null = count($email_info) == 0;
        // else write email to db
        if ($is_email_null) {
            $this->database->insert($database_table_name, array('email' => $email));
            if ($this->database->is_error === null) {
                // get users id
                $email_info = $this->database->select_from($database_table_name, "id", "", false, 0, "email", $email)->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        $user_model = new User_model($database_table_name);
        // return jwt token too
        return $user_model->generate_token($email_info[0]['id']);
    }

    public function signOut($access_token)
    {
        if (is_null($access_token)) return false;

        $this->client->setAccessToken($access_token);
        $this->client->revokeToken();
    }
}
