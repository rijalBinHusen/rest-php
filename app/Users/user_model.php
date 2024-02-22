<?php
// Call dotenv package
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
// load dotenv package
// $dotenv->load();
require_once(__DIR__ . '/../../utils/database.php');

class User_model
{
  private $database = null;
  public $error = null;
  private $table_name = null;

  function __construct($table_name)
  {

    $this->table_name = $table_name;
    $this->database = Query_builder::getInstance();
  }

  // (D) SAVE USER
  function register($name, $email, $password)
  {

    $data_to_entry = array(
      "name" => $name,
      "email" => $email,
      "password" => password_hash($password, PASSWORD_DEFAULT)
    );

    // check is the email exists or no
    $findEmail = $this->database->select_where($this->table_name, "email", $email)->fetch();
    $isEmailExists = is_array($findEmail);

    if ($isEmailExists) {

      $this->error = "User exist.";
    } else {

      $this->database->insert($this->table_name, $data_to_entry);
    }
  }

  function update_password($id_user, $old_password, $new_password)
  {

    // retrieve old password
    $retrieveUser = $this->database->select_where($this->table_name, "id", $id_user)->fetch();
    $isUserExists = is_array($retrieveUser);

    if (!$isUserExists) {

      $this->error = "User exist.";
      // stop here
      return;
    }

    // check is old password matched
    // $old_password_hashed = password_hash($old_password, PASSWORD_DEFAULT);
    // $isOldPasswordMatched = $retrieveUser['password'] === $old_password_hashed;
    $isOldPasswordMatched = password_verify($old_password, $retrieveUser["password"]);

    if (!$isOldPasswordMatched) {

      $this->error = "Old password not matched.";
    }

    // update new password
    else {

      $result = $this->database->update($this->table_name, ['password' => password_hash($new_password, PASSWORD_DEFAULT)], "id", $id_user);
      return $result;
    }
  }

  // (F) VERIFY USER LOGIN
  // RETURNS FALSE IF INVALID EMAIL/PASSWORD
  // RETURNS JWT IF VALID
  function login($email, $password)
  {
    // (F1) GET USER
    $user = $this->database->select_where($this->table_name, "email", $email)->fetch();
    $valid = is_array($user);

    // (F2) CHECK PASSWORD
    if ($valid) {
      $valid = password_verify($password, $user["password"]);
    }

    // (F3) RETURN JWT IF OK, FALSE IF NOT
    if ($valid) {
      $now = strtotime("now");

      return Firebase\JWT\JWT::encode([
        "iat" => $now, // issued at - time when token is generated
        "nbf" => $now, // not before - when this token is considered valid
        "exp" => $now + ((3600 * 24) * 7), // expiry - 7 days (3600 secs * 24 * 7) from now in this example
        "jti" => "RANDOM TOKEN TOKEN RANDOM", // json token id
        "iss" => JWT_ISSUER, // issuer
        "aud" => JWT_AUD, // audience
        "data" => ["id" => $user["id"]] // whatever data you want to add
      ], JWT_SECRET, JWT_ALGO);
    } else {

      $this->error = "Invalid user/password";
    }
  }

  // (G) VALIDATE JWT, RETURN USER IF VALID, RETURN FALSE IF INVALID
  function validate($jwt)
  {

    $valid = false;
    // (G1) "UNPACK" ENCODED JWT
    try {

      $jwt = Firebase\JWT\JWT::decode($jwt, new Firebase\JWT\Key(JWT_SECRET, JWT_ALGO));
      $valid = $jwt;
    } catch (Exception $e) {

      $this->error = $e->getMessage();
    }

    if ($valid) return $valid;

    else $this->error = "Invalid JWT";
  }
}
