<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;

include 'funcs.php';

ParseUser::logOut();

if (ParseUser::getCurrentUser()) { // if user is logged in
  $user = ParseUser::getCurrentUser(); // put the user in $user
} else { // otherwise
  try {
    $user = ParseUser::logIn("rhonda@hackathon.com", "123"); // log the user
    // Do stuff after successful login.
  } catch (ParseException $error) {
    // The login failed. Check error to see why.
    die('Bill could not log in!'); // in case of error fail misserably
  }
}

$requested = false;
$userType = getUserType(ParseUser::getCurrentUser());

if ($userType == 'availableBorrower') {
  // Procceed normally!

  // Check if action was performed.
  if (isset($_POST['action']) && $_POST['action']) {
    $action = $_POST['action'];

    if ($action == 'request') {
      $start = $_POST['start'];
      $end = $_POST['end'];

      $requested = true;

      // Send a request for the specified time.
      createTransaction($start, $end);
    } else {
      // DELETE TRANSACTION.
    }
  }

} else if ($userType == 'seekingBorrower') {
  // Get start and end.

  $requested = true;
  $times = getRegistrationTime();
  $start = $times->start;
  $end = $times->end;

} else if ($userType == 'currentBorrower') {
  header('Location: http://' . URL . 'hackbag/timer.php');
} else if ($userType == 'scheduledBorrower') {
  header('Location: http://' . URL . 'hackbag/scheduled.php');

// LENDERS!
} else if ($userType == 'currentLender') {
  header('Location: http://' . URL . 'hackbag/timer.php');
} else if ($userType == 'scheduledLender') {
  header('Location: http://' . URL . 'hackbag/scheduled.php');
} else if ($userType == 'availableLender') {
  header('Location: http://' . URL . 'hackbag/main.php');
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

    <link href="jquery.timepicker.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/bootstrap-table.min.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="font-size:14px">
    <div class="container">

      <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" style="font-size:30px; margin-top:5px">HackBag</a>
        </div>


        <p class="navbar-text navbar-right" style="margin-top:5px; margin-bottom:5px"><img src="http://st2.depositphotos.com/2571355/7122/v/110/depositphotos_71222099-Sleeping-bag-flat-square-icon-with-long-shadows..jpg" class="img-rounded" alt="Cinque Terre" style="border-radius:50%; width: 50px; height:50px; margin-right: 10px"> </p>


          </div><!-- /.container-fluid -->
        </nav>


      <div class="row">
        <div class="pad-top-lg col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
          <div class="header text-center">
            <div class="profile-content">
              <h1 class="name hidden-xs"> <img src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h1>
              <h2 class="name visible-xs"> <img width="50px" height="50px" src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h2>
              <hr>

              <h4 class="desc pad-top-xs text-success"> <span id="availableSleepingBags">
                <?php echo count(getLenders()) ?></span> Sleeping Bags are available. </h4>

              <form action="." method="post">
                <div id="time-selection" class="row pad-top-sm text-center">
                  <div class="col-md-6">
                    <label class="sr-only">Starting Time</label>
                    <div class="input-group start-div timepicker">
                        <input class="form-control" name="start" id="start" placeholder="2:00 AM">

                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="sr-only">Ending Time</label>

                    <div class="input-group end-div timepicker">
                      <input class="form-control" name="end" id="end" placeholder="4:00 AM">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                  </div>

                  </div>
                </div>

                <div id="time-requested">

                </div>

                <input id="action" type="hidden" name="action" value="request">
                <button id="request" type="submit" class="btn btn-default btn-lg btn-block">Request a Sleeping Bag</button>

              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="jquery.timepicker.js"></script>

    <script>
      $('#start').timepicker();
      $('#end').timepicker();

      <?php

      if ($requested) {

          echo "$('#request').css('background', \"darkgray\");";
          echo "$('#request').css('border-color', \"darkgray\");";
          echo "$('#request').text('Request Pending...');";

          echo " $('#time-selection').hide(); ";
          echo " var start = '$start'; var end = '$end'; ";
          echo " $('#time-requested').html('<h5>You requested a sleeping bag from ' + start + ' to ' + end + '.</h5> <h5> Please wait while we pair you with a hacker. </h5>'); ";
          echo " $('#time-requested').fadeIn(); ";
          echo " $('#action').val('delete'); ";

      }

      ?>

      function listenForUpdate() {
        $.ajax({
          url: "getUpdate.php",
          success: function(type) {
            if (type == 'scheduledLender' || type == 'scheduledBorrower') {
              window.location.href = 'scheduled.php';
            } else if (type == 'currentLender' || type == 'currentBorrower') {
              window.location.href = 'timer.php';
            }
          }
        });
      }

      setInterval(function() {
        listenForUpdate();
      }, 1000);
    </script>
  </body>
</html>
