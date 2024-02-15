<?php
require_once(__DIR__ . '/user_model.php');

class User
{
    protected $user;

    function __construct($table_name)
    {

        $this->user = new User_model($table_name);
    }

    public function login()
    {
        $req = Flight::request();
        $email = $req->data->email;
        $password = $req->data->password;

        $token = $this->user->login($email, $password);
        $errorLogin = $this->user->error;

        if (is_null($errorLogin)) {

            setcookie('JWT-Authorization', $token, time() + 3600, '/', '', false, true);

            Flight::json([
                'success' => true,
                'token' => $token,
            ], 200);
        } else {
            Flight::json([
                'success' => false,
                'message' => $errorLogin
            ], 401);
        }
    }

    public function register()
    {
        $req = Flight::request();
        $email = $req->data->email;
        $password = $req->data->password;
        $name = $req->data->name;

        $invalid_request_body = is_null($email) || is_null($password) || is_null($name) || empty($email) || empty($password) || empty($name);

        if ($invalid_request_body) {

            Flight::json([
                "success" => false,
                "message" => "Unprocessable Entity"
            ], 422);
        } else {

            $this->user->register($name, $email, $password);
            $errorLogin = $this->user->error;

            if (is_null($errorLogin)) {
                Flight::json([
                    'success' => true,
                    'message' => 'Registration success.',
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'message' => $errorLogin,
                ], 409);
            }
        }
    }

    public function check_token()
    {

        $jwt_token = $this->get_jwt_token_on_request();

        if ($jwt_token) {

            $is_token_valid = $this->user->validate($jwt_token);

            if ($is_token_valid) {

                Flight::json([
                    'success' => true,
                    'message' => 'Valid token'
                ], 200);
            } else {

                Flight::json([
                    'success' => false,
                    'message' => 'Invalid token',
                ], 401);
            }
        } else {

            Flight::json([
                'success' => false,
                'message' => 'You must be authenticated to access this resource.',
            ], 401);
        }
    }

    public function is_valid_token()
    {

        $jwt_token = $this->get_jwt_token_on_request();

        if ($jwt_token) {

            $is_token_valid = $this->user->validate($jwt_token);
            if ($is_token_valid) {

                return $is_token_valid;
            } else {

                Flight::json([
                    'success' => false,
                    'message' => 'Invalid token',
                ], 401);

                return false;
            }
        } else {
            Flight::json([
                'success' => false,
                'message' => 'You must be authenticated to access this resource.',
            ], 401);

            return false;
        }
    }

    public function get_user_info()
    {

        $jwt_token = $this->get_jwt_token_on_request();

        if ($jwt_token) {

            $user_info_by_jwt = $this->user->validate($jwt_token);
            if ($user_info_by_jwt) {

                return $user_info_by_jwt;
            } else {

                Flight::json([
                    'success' => false,
                    'message' => 'Invalid token',
                ], 401);
            }
        } else {

            Flight::json([
                'success' => false,
                'message' => 'You must be authenticated to access this resource.',
            ], 401);
        }

        return false;
    }

    public function update_password_by_id($id_user)
    {

        $req = Flight::request();
        $password_old = $req->data->password_old;
        $password_new = $req->data->password_new;

        $invalid_request_body = is_null($password_old) || empty($password_old) || is_null($password_new) || empty($password_new);

        if ($invalid_request_body) {

            Flight::json([
                "success" => false,
                "message" => "Password can't be null or empty"
            ], 400);
            // stop here
            return;
        }

        $row_updated = $this->user->update_password($id_user, $password_old, $password_new);
        $errorUpdateUser = $this->user->error;

        if (is_null($errorUpdateUser) && $row_updated > 0) {

            Flight::json([
                'success' => true,
                'message' => 'Update password success.'
            ]);
        } else {

            Flight::json([
                'success' => false,
                'message' => $errorUpdateUser
            ], 500);
        }
    }

    public function is_admin($id_admin)
    {

        $jwt_token = $this->get_jwt_token_on_request();

        if ($jwt_token) {

            $user_info_by_jwt = $this->user->validate($jwt_token);

            $is_no_error = $this->user->error === null;

            if ($is_no_error) {

                $is_admin = $user_info_by_jwt->data->id == $id_admin;
                if ($is_admin) return true;
            }
        }

        Flight::json([
            'success' => false,
            'message' => 'You must be authenticated to access this resource.',
        ], 401);
    }

    public function get_jwt_token_on_request()
    {

        $cookie_token = $_COOKIE['JWT-Authorization'];
        $is_http_jwt_set = isset($_SERVER['HTTP_JWT_AUTHORIZATION']);

        $is_token_set = $cookie_token || $is_http_jwt_set;

        if ($is_token_set) {

            $jwt_token = "";

            if ($is_http_jwt_set) {

                $jwt_token = $_SERVER['HTTP_JWT_AUTHORIZATION'];
            } else {

                $jwt_token = $cookie_token;
            }

            return $jwt_token;
        } else {

            return false;
        }
    }
}
