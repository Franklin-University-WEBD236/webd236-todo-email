<?php

class UserModel extends Model {

  private static $fieldNames = array('email', 'password', 'firstName', 'lastName', 'token');

  public function __construct($fields = null) {
    parent::__construct($fields);
    foreach (self::$fieldNames as $attribute) {
      if (isset($fields[$attribute])) {
        $this->$attribute = $fields[$attribute];
      } else {
        $this->$attribute = null;
      }
    }
  }
  
  private static function makeUserFromRow($row) {
    $user = null;
    if ($row) {
      $user = new UserModel($row);
    }
    return $user;
  }

  public static function findUserById($id) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE id = ?');
    $st -> bindParam(1, $id);
    $st -> execute();
    return self::makeUserFromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

   public static function findUserByToken($token) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE token = ?');
    $st -> bindParam(1, $token);
    $st -> execute();
    return self::makeUserFromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function findByEmail($email) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE email = :email');
    $st -> bindParam(':email', $email);
    $st -> execute();
    return self::makeUserFromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public function insert() {
    $st = self::$db -> prepare("INSERT INTO user (email, password, firstName, lastName) values (:email, :password, :firstName, :lastName)");
    $st -> bindParam(':email', $this->email);
    $st -> bindParam(':password', $this->password);
    $st -> bindParam(':firstName', $this->firstName);
    $st -> bindParam(':lastName', $this->lastName);
    $st -> execute();
    return $this->id = self::$db->lastInsertId();
  }
/*  
  public static function addUser($email, $password, $firstName="", $lastName="") {
    $st = self::$db -> prepare("INSERT INTO user (email, password, firstName, lastName) values (:email, :password, :firstName, :lastName)");
    $st -> bindParam(':email', $email);
    $st -> bindParam(':password', $password);
    $st -> bindParam(':firstName', $firstName);
    $st -> bindParam(':lastName', $lastName);
    $st -> execute();
    return self::$db->lastInsertId();
  }
*/
  public function update() {
    $st = self::$db -> prepare("UPDATE user SET email = :email, password = :password, firstName = :firstName, lastName = :lastName, token = :token WHERE id = :id");
    $st -> bindParam(':email', $this->email);
    $st -> bindParam(':password', $this->password);
    $st -> bindParam(':firstName', $this->firstName);
    $st -> bindParam(':lastName', $this->lastName);
    $st -> bindParam(':id', $this->id);
    if (!isset($this->token)) {
      $this->token = null;
    }
    $st -> bindParam(':token', $this->token);
    $st -> execute();
  }
}

?>