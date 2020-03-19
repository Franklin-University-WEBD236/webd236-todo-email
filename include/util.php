<?php
  function value(&$something, $default = "") {
    return isset($something) ? $something : $default;
  }

function safeParam($arr, $index, $default="") {
  if ($arr && isset($arr[$index])) {
    if (is_string($arr[$index])) {
      return trim($arr[$index]);
    } else {
      return $arr[$index];
    }
  }
  return $default;
}

function restartSession() {
  //remove PHPSESSID from browser
  if ( isset( $_COOKIE[session_name()] ) ) {
    setcookie( session_name(), "", time()-3600, "/" );
  }
  //clear session from globals
  $_SESSION = array();
  //clear session from disk
  session_destroy();
  session_start();
}

function login($user) {
  $_SESSION['user'] = $user;
}

function isLoggedIn() {
  return isset($_SESSION['user']);
}

function debug($something) {
  echo "<div class='debug'>\n";
  print_r($something);
  echo "\n</div>\n";
}

function getRandomToken($length){
   $token = "";
   $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
   $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
   $codeAlphabet.= "0123456789-_";
   $max = strlen($codeAlphabet);

  for ($i=0; $i < $length; ++$i) {
      $token .= $codeAlphabet[random_int(0, $max-1)];
  }

  return $token;
}

?>