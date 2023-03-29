<?php
// Call dotenv package
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
// load dotenv package
$dotenv->load();

class User_model {
  // (A) CONNECT TO DATABASE
  public $error = null;
  private $pdo = null;
  private $stmt = null;
  function __construct () {
    // get database configuration from dotenv file
    $database = getenv('DATABASE');
    // get username database from dotenv file
    $username = getenv('DATABASE_USER');
    // get password database from dotenv file
    $password = getenv('DATABASE_PASSWORD');
    $this->pdo = new PDO($database, $username, $password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
 
  // (B) CLOSE CONNECTION
  function __destruct () {
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }
 
  // (C) RUN SQL QUERY
  function query ($sql, $data=null) {
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($data);
  }
 
  // (D) SAVE USER
  function save ($name, $email, $password, $id=null) {
    $data = [$name, $email, password_hash($password, PASSWORD_DEFAULT)];
    if ($id===null) {
      // check is the email exists or no
      $findEmail = $this->getUser($email);
      $isEmailExists = is_array($findEmail);
      
      if($isEmailExists) {
        return false;
      }

      $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    } else {
      $sql = "UPDATE `users` SET `name`=?, `email`=?, `password`=? WHERE `id`=?";
      $data[] = [$id];
    }
    $this->query($sql, $data);
    return true;
  }
 
  // (E) GET USER
  function getUser($id) {
    $this->query(
      sprintf("SELECT * FROM `users` WHERE `%s`=?", is_numeric($id) ? "id" : "email" ),
      [$id]
    );
    return $this->stmt->fetch();
  }
 
  // (F) VERIFY USER LOGIN
  // RETURNS FALSE IF INVALID EMAIL/PASSWORD
  // RETURNS JWT IF VALID
  function login ($email, $password) {
    // (F1) GET USER
    $user = $this->getUser($email);
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
      return false;
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
      return false;
    }
 
    // (G2) GET USER
    if ($valid) {
      $user = $this->getUser($jwt->data->id);
      $valid = is_array($user);
    }
 
    // (G3) RETURN RESULT
    if ($valid) {
      unset($user["password"]);
      return $user;
    } else {
      $this->error = "Invalid JWT";
      return false;
    }
  }
}

define("JWT_SECRET", getenv("JWT_SECRET"));
define("JWT_ISSUER", getenv("JWT_ISSUER"));
define("JWT_AUD", getenv("JWT_AUD"));
define("JWT_ALGO", getenv("JWT_ALGO"));
 
// (J) NEW USER OBJECT
$_USER = new User();