<?php
session_start();
// when making admin accounts use md5 hashing method in database, i think if we dont it breaks the login attempt.
// initializing variables
$Ausername = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');

if (isset($_POST['login_admin'])) {
  $Ausername = mysqli_real_escape_string($db, $_POST['Ausername']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($Ausername)) {
  	array_push($errors, "Username is required");
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
  	  header('location: manageUsers.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>