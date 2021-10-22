<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

include "includes/regnav.html";

                if (isset($_POST['verify'])) {
                        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                    $fname = $_SESSION['fname'];
                      $lname = $_SESSION['lname'];
                     $username = $_SESSION['username'];
                     $dob = $_SESSION['dob'];
                     $email = $_SESSION['email'];
                     $password_1 = $_SESSION['password'];
                        
                        
                    $check = $_SESSION['otp'];
                    $otp = $_POST['onetimepass'];
                    $otpstring = "$check";
                    
                    
                    if ($otp == $otpstring) {
                            $password = md5($password_1);//encrypt the password before saving in the database
                          	$query = "INSERT INTO Users (fname, lname, username, dob, email, password) 
                          			  VALUES('$fname', '$lname', '$username', '$dob', '$email', '$password')";
                          	mysqli_query($conn, $query);
                          	unset($_SESSION['fname']);
                          	unset($_SESSION['lname']);
                          	unset($_SESSION['dob']);
                          	unset($_SESSION['email']);
                          	unset($_SESSION['password']);
                          	$_SESSION['success'] = "You are now logged in";
                          	unset($_POST['verify']);
                          	echo "
                          	    <script>
                          	        window.location.href = 'welcome.php';
                          	    </script>
                          	";
                    } else {
                        array_push($errors, "Incorrect One Time Password.");
                    }
                }
?>
<!-- required html -->
<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,500" rel="stylesheet">
<style>
        body {
         font-family: 'Quicksand', sans-serif;
         font-weight: 300;
        }
        
        h1, h2, h3, h4, h5, h6 {
         font-weight: 500;
        }
</style>
</head>


<body>
    <?php include "includes/carousel.html" ?>

<!-- end of navigation -->
<!-- Layout and Registration form -->
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-4">
        <!-- Login Form -->
       <form class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body" method="post" style="padding:10px";>
  <div class="mb-3">
    <center>
      <IMG id="namebanner" SRC="images/logo.png" width="80%" >
         </center>
          <?php include('errors.php'); ?>
          <div class="text-left">
          <h4 class="modal-title">One Time Password</h4>
              
                <?php
                    $fname = $_SESSION['fname'];
                      $lname = $_SESSION['lname'];
                     $username = $_SESSION['username'];
                     $dob = $_SESSION['dob'];
                     $email = $_SESSION['email'];
                     $password_1 = $_SESSION['password'];
                ?>
                  <div class="mb-3">
                    <label for="onetimepass" id="onetimepasstext" class="form-label">Enter the one time password sent to: <a style="font-weight:bold"><?php echo "$email"; ?></a> </label>
                    <input type="text" class="form-control" id="onetimepass" name="onetimepass" >
                  </div>
                  <div class="text-center">
                      <button id="otpinput" type="submit" class="btn btn-warning" name="verify">Verify</button>
                  </div></form>
    </div>
    <div class="col col-lg-2">
    </div>
  </div>
</div>
        <br>
        <br>
        <br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<!-- tried adding button that would stick to the top of the footer "add post" user simple presses button at the bottom to be taken to newpost.php simply convinient for phone users COULD ALSO BE AN IMAGE and we could align it on top of footer in the middle (like a little plus button)-->
<!-- <div class="d-grid gap-2">
  <button id="createpost" onclick="location.href='newpost.php'" class="btn btn-warning" type="button">Add Post</button>
</div> -->
<nav class="navbar fixed-bottom navbar-light bg-success bg-gradient ">
  <div class="container-fluid ">
      
    <a class="navbar-brand" href="#">© Fringelogic Group 17</a>
    <a class="navbar-text navbar-right">Powered by Bootstrap</a>
  </div>
</nav>
</body>
</html>
