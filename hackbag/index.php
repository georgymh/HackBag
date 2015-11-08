<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;

if (ParseUser::getCurrentUser()) { // if user is logged in
  $user = ParseUser::getCurrentUser(); // put the user in $user
} else { // otherwise
  try {
    $user = ParseUser::logIn("bill@hackathon.com", "billbill"); // log the user
    // Do stuff after successful login.
  } catch (ParseException $error) {
    // The login failed. Check error to see why.
    die('Bill could not log in!'); // in case of error fail misserably
  }
}

// BODY

// Get user
$name = $user->get('fullName'); // get user's name
echo "<h1>Hi $name</h1>"; // print it

// Get hackathon
$CITRUS_HACK_ID = 'QpZGpVa5oc';
$query = new ParseQuery("Hackathon");
try {
  $currentHackathon = $query->get("QpZGpVa5oc");
  // The object was retrieved successfully.
} catch (ParseException $ex) {
  // The object was not retrieved successfully.
  // error is a ParseException with an error code and message.
}

// Get other users
$query = new ParseQuery("_User");
$query->equalTo("currentHackathon", $currentHackathon);
$results = $query->find();
$numberOfUsers = count($results);
//echo "<h2>$numberOfUsers are online!</h2>";
foreach ($results as $result){
  echo $result->get('fullName') . "</br>";
}
?>
