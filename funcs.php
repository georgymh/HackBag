<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;

function getLenders(){
  $query = new ParseQuery("_User");
  $query->equalTo("ownsBag", true);
  $results = $query->find();

  return $results;
}

function getSleepers(){
  $query = new ParseQuery("BagTransaction");
  $query->equalTo("status", "active");
  $results = $query->find();

  return $results;
}

$var = getSleepers();
foreach ($var as $v){
  echo $v->get('objectId') . "</br>" . "test";
}
echo "test";

?>
