<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!-- CHANNELS PAGE -->
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
    <style>
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary
        {
            background-color:#8FBC8F;
            border-color: #8FBC8F;
            color:#fff;
        }
        .btn-primary
        {
            color:#fff;
            background-color:#20B2AA;
            border-color: #20B2AA;
        }
    </style>
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html" ?>
        <div class="container">
            <br>
            
        <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-5 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Change Username</h2>
             </div></div>
            </div>
            <div class = "col-lg-4 col-md-3">
            </div>
            <div class = " d-none d-md-block col-lg-3 col-md-4">
                <div class = "card border-2 border-dark shadow-lg">
                <div class = "card body border-0 m-2" align="center">
                <h2>Notifications</h2>
                </div>
                </div>
            </div>
            
             
        </div><br>
    <div class="row row-cols-2">
    
    <!-- Main content of current page -->
    <div class="col-md-8 col-12 d-block">
    <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
        <div class = "card border-0">
        <div class = "card body border-0">
            
            <ul class="list-group list-group-flush">
            
            <li class="list-group-item border-0">
                <form method="post">
                    <div class="input-group">
                        <label id="newusernametext">Enter your new username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input id="username" type="text" name="username_1">
                    </div>
                
                    <div class="input-group">
                        <label id="renewusernametext">Re-enter your new username:&nbsp;&nbsp;</label>
                        <input id="reusername" type="text" name="username_2">
                    </div>
                    <br>
                    <div class="input-group">
                        <button id="confirm" type="submit" class="btn btn-primary btn-sm" name="changeusername" onclick= "confirm('Are you sure you want to change your username?')">Set New Username</button>
                    </div>
                </form>
            </li>
        </div>
        </div>
    <?php
    if (isset($_POST['changeusername'])) {
        $username = $_SESSION['username'];
        $username1 = $_POST['username_1'];
        $username2 = $_POST['username_2'];
        
        $i = 0;
        
     
        
        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if (strlen($username1) > 20) {
      	    echo '<p id="error1">Username is too long please keep it under 20 characters.</p>';
      	    $i++;
        }
    
        if (empty($username1)) {
      	    echo '<p id="error1">New username required.</p>';
      	    $i++;
        }
        if (empty($username2)) {
      	    echo '<p id="error2">Repeat new username is required.</p>';
      	    $i++;
        }
        if (!($username1 == $username2)) {
            echo '<p id="error3">Repeat new username must match new username.</p>';
            $i++;
        }
        
        // check new username is not already in use
        $user_check_query = "SELECT * FROM Users WHERE username='$username1'";
        $result = mysqli_query($conn, $user_check_query);
        $user = mysqli_fetch_assoc($result);
  
        if ($user) { // if user exists
            if ($user['username'] === $username1) {
                echo '<p id="error3">Username already exists. Choose another new username.</p>';
                $i++;
            }
        }
        if ($i == 0) {
          	$sql = "UPDATE Users SET username='$username1' WHERE username='$username'";
          	
          	mysqli_query($conn, $sql);
          	
          	if ($conn->query($sql) === TRUE) {
                echo "Username changed successfully";
                unset($_SESSION['username']);
              	$_SESSION['username'] = $username1;
              	header("location: myaccount.php");
            } else {
              echo "Error changing username: " . $conn->error;
            }
        }
    }
?>
    </div>
    </div>
    
        <?php include "includes/notifications.php" ?>
    
  </div>
</div>
    <?php include "includes/footer.php" ?>
    

</body>
</html>