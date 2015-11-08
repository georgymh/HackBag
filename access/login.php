<?php

include '../parse.php';
use Parse\ParseUser;

$user = $_POST['user'];
$pass = $_POST['pass'];

try {
  $user = ParseUser::logIn( $user , $pass );
  // Do stuff after successful login.
  echo 1;
} catch (ParseException $error) {
  // The login failed. Check error to see why.
  echo 0;
}

?>
