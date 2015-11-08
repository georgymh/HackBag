<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;

//gets array of users with available bags
function getLenders(){
  $query = new ParseQuery("_User");
  $query->equalTo("ownsBag", false);
  $results = $query->find();

  return $results;
}

//gets number of current sleepers
function getNumOfSleepers(){
  $query = new ParseQuery("BagTransaction");
  $query->startsWith("status", "active");
  $results = $query->find();
  $count = count($results);

  return $count;
}

//toggle seeking value
function toggleSeeking($user){
  $user->set("seeking", !$user->get("seeking"));
}

//get array of users seeking bags
function getSeekers(){
  $query = new ParseQuery("_User");
  $query->equalTo("seeking", true);
  $results = $query->find();
  return $results;
}

// function getType($user){
//
// }



$var = findRandomLender();
echo $var->get("fullName") . "</br>";

?>
