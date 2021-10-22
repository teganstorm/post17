<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

include "includes/regnav.html";

        // REGISTER USER
        if (isset($_POST['reg_user'])) {
            
            // connect to the database
            $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
          
          // receive all input values from the form
          $fname = mysqli_real_escape_string($db, $_POST['fname']);
          $lname = mysqli_real_escape_string($db, $_POST['lname']);
          $dob = mysqli_real_escape_string($db, $_POST['dob']);
          $username = mysqli_real_escape_string($db, $_POST['username']);
          $email = mysqli_real_escape_string($db, $_POST['email']);
          $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
          $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
          
          
        
          // form validation: ensure that the form is correctly filled ...
          // by adding (array_push()) corresponding error unto $errors array
          if (empty($fname)) { array_push($errors, "First name is required"); }
          if (empty($lname)) { array_push($errors, "Last name is required"); }
          if (empty($dob)) { array_push($errors, "Date of Birth is required"); }
          if (empty($username)) { array_push($errors, "Username is required"); } // The problem with login : when you try to login without inputting username (only password) the error "username is required" pops out twice probably because it somehow connects also to register side of code
          if (strlen($username) > 20) { array_push($errors, "Username is too long please keep it under 20 characters!");}
          if (empty($email)) { array_push($errors, "Email is required"); }
          
          if (empty($password_1)) {
              {array_push($errors, "Please enter in a password") ; }
            }
          if (strlen($password_1) <= '8') {
                {array_push($errors, "Your Password Must Contain At Least 8 Characters!"); }
            }
          if(!preg_match("#[0-9]+#",$password_1)) {
                {array_push($errors, "Your Password Must Contain At Least 1 Number!"); }
            }
          if(!preg_match('/[^a-zA-Z\d]/', $password_1)) {
                {array_push($errors, "Your Password Must Contain At Least 1 Special Character !");  }
            }
        
         if($password_1 <> $password_2) {
             {array_push($errors, "Your Passwords dont match!");  }
         }
         
          // first check the database to make sure 
          // a user does not already exist with the same username and/or email
          $user_check_query = "SELECT * FROM Users WHERE username='$username' OR email='$email' LIMIT 1";
          $result = mysqli_query($db, $user_check_query);
          $user = mysqli_fetch_assoc($result);
          
          if ($user) { // if user exists
            if ($user['username'] === $username) {
              array_push($errors, "Username already exists");
            }
        
            if ($user['email'] === $email) {
              array_push($errors, "email already exists");
            }
          }
        
          $currDate = date("d-m-Y"); //today's date
        
          $userBDay = new DateTime($dob);
          $currDate = new DateTime($currDate);
           
          $interval = $userBDay->diff($currDate);
        
          $myage = $interval->y; 
          if ($myage < 12){
              array_push($errors, "You must be at least 12 years old to create an account");
          }
        
        
          // Finally, register user if there are no errors in the form
          if (count($errors) == 0) {
              
              $_SESSION['fname'] = $fname;
              $_SESSION['lname'] = $lname;
              $_SESSION['username'] = $username;
              $_SESSION['dob'] = $dob;
              $_SESSION['email'] = $email;
              $_SESSION['password'] = $password_1;
              
            $six_digit_random_number = mt_rand(100000, 999999);
            $_SESSION['otp'] = $six_digit_random_number;
            $subject = "Post17 One Time Password";
            $message = "Your one time password: \n$six_digit_random_number";
                    
            mail($email, $subject, $message);
           
              unset($_POST['reg_user']);
                          	echo "
                          	    <script>
                          	        window.location.href = 'regotp.php';
                          	    </script>
                          	";
              
          	
          	
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
        .hide {
         display: none;
}   
        .myDIV:hover + .hide {
        display: block;
        color: red;
}
      
</style>
</head>


<body>
    <?php include "includes/carousel.html" ?>

<!-- end of navigation -->
<!-- Layout and Registration form -->
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-2">
    </div>
    <div class="col-md-auto">
        <!-- Login Form -->
       <form class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-7 bg-body" method="post" style="padding:10px";>
  <div class="mb-3">
    <div class="text-left">
      <IMG id="namebanner" SRC="images/logo.png" width="300" heigth="260">
          <?php include('errors.php'); ?>
          <h4 class="modal-title">Create your Account</h4>
    </div>
    <label for="fnametext" id="fnametext" class="form-label">First name</label>
    <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>">
  </div>
  <div class="mb-3">
    <label for="lnametext" id="lnametext" class="form-label">Last Name</label>
    <input type="lname" type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname; ?>">
  </div>
  <div class="mb-3">
    <label for="usernametext" id="usernametext" class="form-label">Username</label>
    <input type="username" type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
  </div>
  <div class="mb-3">
    <label for="emailtext" id="emailtext" class="form-label">Email</label>
    <input type="email" type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
  </div>
  <div class="mb-3">
    <label for="birthtext" id="birthtext" class="form-label">Date of Birth</label>
    <input type="date" class="form-control" id="birth" name="dob" value="<?php echo $dob; ?>">
  </div>
  <div class="mb-3">
    <label for="passwordtext" id="passwordtext" class="form-label">Password
    </label>
    <input type="password" type="password" class="form-control" id="password" name="password_1" >
     <div id="i" onclick="myFunction()" style="color:red" title="Your Password Must Contain At Least 8 Characters, 1 Number And 1 Special Character!">(i)</i>
    </div>
    <script>
      function myFunction() {
        document.getElementById("i").innerHTML = "Your Password Must Contain At Least 8 Characters,<br> 1 Number And 1 Special Character!";
      }
    </script>
  </div>
  <div class="mb-3">
    <label for="repasswordtext" id="repasswordtext" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="repassword" name="password_2" >
  </div>
  <div class="text-center">
       <p>
         By completing registration, you certify that you have read<br>  and agree to the Terms and Conditions of use. <a href="tmc.php"> Find out more. </a> 
    </p>
  <button id="inputbutton" type="submit" class="btn btn-warning" name="reg_user">Register</button> <br> 
   
   
  </div>
  <div class="text-center">
  <a class="text-center" href="login.php">Already a member? Login</a>
  </div>
</form>
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