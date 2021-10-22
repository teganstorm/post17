<?php include('server.php') ?>
<?php
session_start(); 
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);



  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM 'Users' WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['password'] = $password;
  	  $_SESSION['previous_location'] = array();
  	  $page = $_SERVER["REQUEST_URI"];
  	  $_SESSION['page'] = $page;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: login.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  		
  	}
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
    <?php include "includes/carousel.html"?>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky-top navbar-light shadow-lg p-3 mb-5 bg-success bg-gradient">
  <div class="container-fluid">
    <a class="navbar-brand" href="login.php">
      <img id="roundedlogo" src="images/final_logo.png" style="border-radius: 25px;" height="60" alt="Logo"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="login.php"><b>Login</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="color:#f4e9be;" href="register.php"><b>Register</b></a>
        </li>
      </ul>
    </div>

  </div>
</nav>
<!-- end of navigation -->
<!-- Layout and Login Form -->
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-2">
    </div>
    <div class="col-md-auto">
        <!-- Login Form -->
       <form class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body" method="post" action="login.php" style="padding:10px";>
  <div class="mb-3">
    <div class="text-center">
      <IMG id="namebanner" SRC="images/logo.png" width="300" heigth="260">
          <?php include('errors.php'); ?>
          <h4 class="modal-title">Member Login</h4>
    </div>
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" aria-describedby="usernHelp" name="username">
    <div id="usernHelp" class="form-text">Login with your unique username.</div>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password">
    <div class="form-check">
  <input class="form-check-input" type="checkbox" onclick="showhidepass()" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Show Password
  </label>
</div>
  </div>
  <div class="text-center">
      <script>
      function showhidepass() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}</script>
  <button id="inputbutton" type="submit" class="btn btn-warning" name="login_user">Login</button>
  </div>
  <div class="text-center">
  <a class="text-center" href="register.php" style="text-decoration:none"  style="color:#ff0000">Sign up for Post17</a>
  </div>
</form>
    </div>
    <div class="col col-lg-2">
    </div>
  </div>
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

