<?php include('serveradmintest.php') 

?>
<!-- required html -->
<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky-top navbar-light shadow-lg p-3 mb-5 bg-success bg-gradient">
  <div class="container-fluid">
       <ul class="navbar-nav mx-auto">
    <li class="nav-item text-center">
      <img src="images/final_logo.png" class="text-center" height="50" alt="Logo">
    </li>
    </div>
  </div>
</nav>
<!-- end of navigation -->
<!-- new body and admin login -->
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-2">
    </div>
    <div class="col-md-auto">
        <!-- Admin Login Form -->
       <form class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body" method="post" action="adminlogin.php" style="padding:10px";>
  <div class="mb-3">
    <div class="text-center">
      <IMG id="namebanner" SRC="images/logo.png" width="300" heigth="260">
          <?php include('errors.php'); ?>
          <h4 class="modal-title">Admin Login</h4>
    </div>
    <label for="Ausername" class="form-label">Username</label>
    <input type="text" class="form-control" id="Ausername" aria-describedby="usernHelp" name="Ausername">
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
  <button id="inputbutton" type="submit" class="btn btn-warning" name="login_admin">Admin Login</button>
  </div>
</form>
    </div>
    <div class="col col-lg-2">
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </form>
    <nav class="navbar fixed-bottom navbar-light bg-success bg-gradient ">
  <div class="container-fluid ">
      
    <a class="navbar-brand" href="#">© Fringelogic Group 17</a>
    <a class="navbar-text navbar-right">Powered by Bootstrap</a>
  </div>
</nav>
</body>
</html>

<?php
if (isset($_POST['login_admin'])) {
    session_start();
  $username = mysqli_real_escape_string($db, $_POST['Ausername']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Ausername is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM admins WHERE Ausername='$Ausername' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['Ausername'] = $Ausername;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: admin_authentication.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>