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
  private $table_name = null;

  function __construct ($table_name) {

    $this->table_name = $table_name;
    $this->database = Query_builder::getInstance();
  }
 
  // (D) SAVE USER
  function save ($name, $email, $password, $id=null) {

    $data_to_entry = array(
      "name" => $name,
      "email" => $email,
      "password" => password_hash($password, PASSWORD_DEFAULT)
    );

    // register
    if ($id===null) {
      // check is the email exists or no
      $findEmail = $this->database->select_where($this->table_name, "email", $email)->fetch();
      $isEmailExists = is_array($findEmail);
      
      if($isEmailExists) {

        $this->error = "User exist.";
      } 
      
      else {

        $this->database->insert($this->table_name, $data_to_entry);
      }

    } 

    // update password
    else {

       $result = $this->database->update($this->table_name, ['password' => password_hash($password, PASSWORD_DEFAULT)], "id", $id);
       return $result;
    }
  }
 
  // (F) VERIFY USER LOGIN
  // RETURNS FALSE IF INVALID EMAIL/PASSWORD
  // RETURNS JWT IF VALID
  function login ($email, $password) {
    // (F1) GET USER
    $user = $this->database->select_where($this->table_name, "email", $email)->fetch();
    $valid = is_array($user);
 
    // (F2) CHECK PASSWORD
    if ($valid) { $valid = password_verify($password, $user["password"]); }
 
    // (F3) RETURN JWT IF OK, FALSE IF NOT
    if ($valid) {
      $now = strtotime("now");

      return Firebase\JWT\JWT::encode([
        "iat" => $now, // issued at - time when token is generated
        "nbf" => $now, // not before - when this token is considered valid
        "exp" => $now + (3600 * 24), // expiry - 1 day (3600 secs * 24) from now in this example
        "jti" => "RANDOM TOKEN TOKEN RANDOM", // json token id
        "iss" => JWT_ISSUER, // issuer
        "aud" => JWT_AUD, // audience
        "data" => ["id" => $user["id"]] // whatever data you want to add
      ], JWT_SECRET, JWT_ALGO);
    } 
    
    else {

      $this->error = "Invalid user/password";
    }
  }
 
  // (G) VALIDATE JWT, RETURN USER IF VALID, RETURN FALSE IF INVALID
  function validate ($jwt) {

    $valid = false;
    // (G1) "UNPACK" ENCODED JWT
    try {

      $jwt = Firebase\JWT\JWT::decode($jwt, new Firebase\JWT\Key(JWT_SECRET, JWT_ALGO));
      // $valid = is_object($jwt);
      $valid = $jwt;
    } catch (Exception $e) {
      
      $this->error = $e->getMessage();
    }
 
    // (G2) GET USER
    if ($valid) {
      // $user = $this->database->select_where($this->table_name, "id", $jwt->data->id)->fetchAll(PDO::FETCH_ASSOC);
      // $valid = is_array($user);
      return $valid;
    }
 
    // (G3) RETURN RESULT
    // if ($valid) {

    //   unset($user["password"]);
    //   return $user;
    // } 
    
    else {

      $this->error = "Invalid JWT";
    }
  }
}