<?php
// Call dotenv package
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
// load dotenv package
// $dotenv->load();
require_once(__DIR__ . '/../../utils/database.php');

define("JWT_SECRET", "SECRET-KEY");
define("JWT_ISSUER", "johndoe");
define("JWT_AUD", "site.com");
define("JWT_ALGO", "HS256");


class User_model {
  private $database = null;
  public $error = null;
  function __construct () {
    $this->database = Query_builder::getInstance();
  }
 
  // (D) SAVE USER
  function save ($name, $email, $password, $id=null) {

    if ($id===null) {
      // check is the email exists or no
      $findEmail = $this->database->select_where("users", "email", $email)->fetch();
      $isEmailExists = is_array($findEmail);
      
      if($isEmailExists) {
        $this->error = "User exist.";
        return;
      }

      $data_to_entry = array(
        "name" => $name,
        "email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT)
      );

      $this->database->insert("users", $data_to_entry);

    } 
    else {
        $data_to_entry = array(
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        );
        $this->database->update("users", $data_to_entry, "id", $id);
    }
    return;
  }
 
  // (F) VERIFY USER LOGIN
  // RETURNS FALSE IF INVALID EMAIL/PASSWORD
  // RETURNS JWT IF VALID
  function login ($email, $password) {
    // (F1) GET USER
    $user = $this->database->select_where("users", "email", $email)->fetch();
    $valid = is_array($user);
 
    // (F2) CHECK PASSWORD
    if ($valid) { $valid = password_verify($password, $user["password"]); }
 
    // (F3) RETURN JWT IF OK, FALSE IF NOT
    if ($valid) {
      $now = strtotime("now");
      return Firebase\JWT\JWT::encode([
        "iat" => $now, // issued at - time when token is generated
        "nbf" => $now, // not before - when this token is considered valid
        "exp" => $now + 3600, // expiry - 1 hr (3600 secs) from now in this example
        "jti" => "RANDOM TOKEN TOKEN RANDOM", // json token id
        "iss" => JWT_ISSUER, // issuer
        "aud" => JWT_AUD, // audience
        "data" => ["id" => $user["id"]] // whatever data you want to add
      ], JWT_SECRET, JWT_ALGO);
    } else {

      $this->error = "Invalid user/password";
      return;

    }
  }
 
  // (G) VALIDATE JWT
  // RETURN USER IF VALID
  // RETURN FALSE IF INVALID
  function validate ($jwt) {
    // (G1) "UNPACK" ENCODED JWT
    try {

      $jwt = Firebase\JWT\JWT::decode($jwt, new Firebase\JWT\Key(JWT_SECRET, JWT_ALGO));
      $valid = is_object($jwt);

    } catch (Exception $e) {
      
      $this->error = $e->getMessage();
      return;

    }
 
    // (G2) GET USER
    if ($valid) {
      $user = $this->database->select_where("users", "id", $jwt->data->id)->fetch();
      $valid = is_array($user);
    }
 
    // (G3) RETURN RESULT
    if ($valid) {

      unset($user["password"]);
      return $user;

    } else {

      $this->error = "Invalid JWT";
      return;

    }
  }
}