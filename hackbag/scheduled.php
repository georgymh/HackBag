<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;
include 'funcs.php';

$user = ParseUser::getCurrentUser();
$type = getUserType($user);
$trans = $user->get('currentTransaction');
$trans->fetch();

if ($type == 'scheduledBorrower') {
  $otherUser = $trans->get('lender');
  $otherUser->fetch();

  $msg = 'has accepted your sleeping bag request.';
  $acceptMsg = "I Received the Sleeping Bag";
} else if ($type == 'scheduledLender') {
  $otherUser = $trans->get('borrower');
  $otherUser->fetch();

  $msg = 'will be RESTing on your sleeping bag.';
  $acceptMsg = "Hand-off Complete";
}

$thirdPartyName = $otherUser->get('fullName');
$start = $trans->get('startTime');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> HackBag - Scheduled </title>

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

       <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
       
          <a class="navbar-brand" href="#" style="font-size:30px; margin-top:5px">HackBag</a>
        </div>
        
    
        <span class="navbar-text navbar-right " style="margin-top:5px; margin-bottom:5px"> <img src="http://st2.depositphotos.com/2571355/7122/v/110/depositphotos_71222099-Sleeping-bag-flat-square-icon-with-long-shadows..jpg" class="img-rounded" alt="Cinque Terre" style="border-radius:50%; width: 50px; height:50px; margin-right: 10px"> </span>

        


      </div><!-- /.container-fluid -->
    </nav>


      <div class="row"> 
        <div class="pad-top-lg col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
          <div class="header text-center">
            <div class="profile-content">
            
              <h1 class="name hidden-xs"> <img src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h1>
              <h2 class="name visible-xs"> <img width="50px" height="50px" src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h2>
               <h3 id="thirdPartyName" class="desc pad-top-sm"><strong> <?php echo $thirdPartyName; ?> </strong></h3>
               <h3 class="desc"> <?php echo $msg; ?> Please proceed to the common area at <?php echo $start; ?>.</h3>
            
               <div class="row">
              <button id="accept" type="button" class="btn btn-success btn-lg btn-block"><?php echo $acceptMsg; ?></button>
            </div>
            </div>

            
          </div>
        </div>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>