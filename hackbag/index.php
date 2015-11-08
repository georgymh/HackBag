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
//echo "<h1>Hi $name</h1>"; // print it

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
  //echo $result->get('fullName') . "</br>";
}


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> HackBag - <?php $currentHackathon->get('name'); ?> </title>

    <!-- Bootstrap -->
    <link href="https://bootswatch.com/cosmo/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    
    <!-- Style -->
    <link href="style.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="font-size:14px">
    <div class="container">
      <div class="row"> 
        <div class="pad-top-lg col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
          <div class="header text-center">
            <div class="profile-content">
              <h1 class="name hidden-xs"> <img src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h1>
              <h2 class="name visible-xs"> <img width="50px" height="50px" src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h2>
              <hr>

              <h4 class="desc pad-top-xs"> <span id="availableSleepingBags">12</span> Sleeping Bags are available. </h4>

              <button id="request" type="button" class="btn btn-default btn-lg btn-block hidden-xs">Request a Sleeping Bag</button>
              <button id="request" type="button" class="btn btn-default btn-md btn-block visible-xs">Request a Sleeping Bag</button>

            </div>
          </div>
        </div>
      </div>

      <div>

        <div class="row pad-top">

        </div>
        <div class="row pad-top">

        </div>
        <div class="row pad-top">

        </div>

      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>