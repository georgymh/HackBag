$(document).ready( function() {

  $('#login').on('click', function (e) {

  e.preventDefault();

  window.location.href = '../hackbag/';

  // Parse.initialize("ejDpZA5bF1CSLb7rN18isGvFMGRcrtVa0rQnPKft", "Pl5o8KxlbX8o236Q5ckbnAfd1Dv8ef6cWV5hrUTo");

  // var user = $('#email').val();
  // var pass = $('#pass').val();

  // Parse.User.logIn(user, pass, {
  //   success: function(user) {
  //     // Do stuff after successful login.
  //     alert('Correct!');
  //   },
  //   error: function(user, error) {
  //     // The login failed. Check error to see why.
  //     alert('Bad credentials!');
  //   }
  // });

});

});



// $(document).ready( function() {
//
//   $('#login').on('click', function (e) {
//
//   e.preventDefault();
//
//   var user = $('#email').val();
//   var pass = $('#pass').val();
//
//   $.ajax({
//     url: "login.php",
//     method: "POST",
//     data: { user: user, pass: pass },
//     success: function(obj) {
//
//       if (obj == 1) {
//         alert('Success');
//       } else {
//         alert('Bad Credentials');
//       }
//
//       console.log(obj);
//     }
//   });
//
// });
//
// });
