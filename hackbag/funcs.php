<?php

include_once '../parse.php';
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

//gets registration time of current user
function getRegistrationTime(){
  $user = ParseUser::getCurrentUser();
  $user->fetch();
  $currentTransaction = $user->get("currentTransaction");
  $currentTransaction-> fetch();

  $times = new stdClass();
  $times->start = $currentTransaction->get("startTime");
  $times->end = $currentTransaction->get("endTime");
  return $times;
}

//get list of seekers in JSON format
function getSeekerList(){
  $seekers = getSeekers();
  $users = array();
  foreach ($seekers as $seeker){
    $transaction = $seeker->get("currentTransaction");
    $transaction->fetch();
    $startTime = $transaction->get("startTime");
    $endTime = $transaction->get("endTime");

    $user = array(
      "id" => $seeker->getObjectId(),
      "name" => $seeker->get("fullName"),
      "start" => $startTime,
      "end" => $endTime
    );

    $users[] = $user;
  }

  $json = json_encode($users);
  return $json;
}

//add new BagTransaction to parse with string times as params
function createTransaction($startTime, $endTime){
  $user = ParseUser::getCurrentUser();
  $transaction = new ParseObject("BagTransaction");
  $transaction->set("borrower", $user);
  $transaction->set("startTime", $startTime);
  $transaction->set("endTime", $endTime);
  $transaction->save();
  $user->set("currentTransaction", $transaction);
  $user->save();
}

//set transaction state to scheduled and assign lender
function scheduleTransaction($lender, $borrowerID){
  $query = new ParseQuery("_User");
  $borrower = $query->get($borrowerID);
  $transaction = $borrower->get("currentTransaction");
  $transaction->fetch();
  $transaction->set("status", "scheduled");
  $transaction->set("lender", $lender);
  $transaction->save();

  $lender->set('currentTransaction', $transaction);
  $lender->save();
}

function activateTransaction(){
  $user = ParseUser::getCurrentUser(); //get user
  $user->fetch(); //refresh user
  $transaction = $user->get("currentTransaction"); //get transaction pointer
  $transaction->fetch(); //dereference pointer
  //if both true

  if ($transaction->get("flag")) {
    // activate it
    $transaction->set("status", "active"); //activate transaction
    $transaction->set("flag", false); // reset
    $transaction->save(); //save transaction
  } else {
    // activate
    $transaction->set("flag", true);
    $transaction->save(); //save transaction
  }
}

function deactivateTransaction(){
  $user = ParseUser::getCurrentUser(); //get user
  $user->fetch(); //refresh user
  $transaction = $user->get("currentTransaction"); //get transaction pointer
  $transaction->fetch(); //dereference pointer
  //if both true

  if ($transaction->get("flag")) {
    // delete
    //clean borrower's currentTransaction
    //$borrower = $transaction->get("borrower");
    //$borrower->fetch();
    //$borrower->delete("currentTransaction");
    //$borrower->save();

    // CANT DELETE ANOTHER USER...
    //clean lender's currentTransaction
    //$lender = $transaction->get("lender");
    //$lender->fetch();
    //$lender->delete("currentTransaction");
    //$lender->save();

    $transaction->destroy();
    //$transaction->save(); //save transaction
  } else {
    // activate flag
    $transaction->set("flag", true);

    // Disassociate user
    $user->delete('currentTransaction');
    $user->save();

    $transaction->save(); //save transaction
  }
}

//get user object by name
function getUserByName($name){
  $query = new ParseQuery("_User");
  return $query->equalTo($name);
}

deactivateTransaction();
