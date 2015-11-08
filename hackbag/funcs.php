<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;
use Parse\ParseObject;

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

//get array of users seeking bags
function getSeekers(){
  $query = new ParseQuery("BagTransaction");
  $query->doesNotExist("status");
  $results = $query->find();
  $users = array();

  //convert BagTransactions to Users
  foreach ($results as $result){
    $user = $result->get("borrower");
    $user->fetch();
    $users[] = $user;
  }

  return $users;
}

//get type of user
function getUserType(&$user){
  if ($user->get("ownsBag")){
    if ($user->get("currentTransaction") == NULL){
      return "availableLender";
    }

    $currentTransaction = $user->get("currentTransaction");
    $currentTransaction->fetch();
    if ($currentTransaction->get("status") == "active"){
      return "currentLender";
    }
    else if ($currentTransaction->get("status") == "scheduled"){
      return "scheduledLender";
    }
  }
  else {
    if ($user->get("currentTransaction") == NULL){
      return "availableBorrower";
    }

    $currentTransaction = $user->get("currentTransaction");
    $currentTransaction->fetch();
    if ($currentTransaction->get("status") == "active"){
      return "currentBorrower";
    }
    else if ($currentTransaction->get("status") == "scheduled"){
      return "scheduledBorrower";
    }
    else if ($currentTransaction->get("lender") == NULL){
      return "seekingBorrower";
    }
  }
}

//add new BagTransaction to parse with string times as params
function createTransaction($startTime, $endTime){
  $user = ParseUser::getCurrentUser();
  $transaction = new ParseObject("BagTransaction");
  $transaction->set("borrower", $user);
  $transaction->set("startTime", $startTime);
  $transaction->set("endTime", $endTime);
  $transaction->save();
}

//gets registration time of current user
function getRegistrationTime(){
  $user = ParseUser::getCurrentUser();
  $user->fetch();
  $currentTransaction = $user->get("currentTransaction");
  $currentTransaction-> fetch();

  //JSON convert
  $times = new stdClass();
  $times->start = $currentTransaction->get("startTime");
  $times->end = $currentTransaction->get("endTime");
  $json = json_encode($times);
  echo $json;
}

?>
