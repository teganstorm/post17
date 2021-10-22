<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');

// REGISTER USER
if (isset($_POST['reg_user'])) {
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
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO Users (fname, lname, username, dob, email, password) 
  			  VALUES('$fname', '$lname', '$username', '$dob', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: feed.php');
  }
}
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM Users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: feed.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>