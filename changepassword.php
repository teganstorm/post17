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
            <div class = "col-md-3 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Change Password</h2>
             </div></div>
            </div>
            <div class = "col-md-5">
            </div>
            <div class = " d-none d-sm-block col-lg-3 col-md-4">
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
            
            <li class="list-group-item">
                <form method="post">
                    
                              

        
                <div class="input-group">
                    <label id="oldpasswordtext">Enter your current password:&nbsp;&nbsp; </label>
                    <input id="oldpassword" type="password" name="password_0">
                </div>
        
                <div class="input-group">
                    <label id="newpasswordtext">Enter your new password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
                    <input id="password" type="password" name="password_1">
                </div>
        
                <div class="input-group">
                    <label id="renewpasswordtext">Re-enter your new password:&nbsp;&nbsp; </label>
                    <input id="repassword" type="password" name="password_2">
                </div>
        <br>
        <div class="input-group">
            <button type="submit" class="btn btn-primary btn-sm" name="changepassword" onclick="confirm('Are you sure you want to change your password?')">Set New Password</button>
            <!-- "confirm('Are you sure you want to change your password?')" -->
        </div>
    </form>
    </li>
    </div>
    </div>
        <?php
        if (isset($_POST['changepassword'])) {
            $errors = array(); 
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            if ($conn->connect_error) {
                      die("Connection failed: " . $conn->connect_error);
            }
            $username = $_SESSION['username'];
            
            
              $query_userid = "SELECT * FROM Users WHERE username='$username'";
              $result_users = mysqli_query($conn, $query_userid);
              $user = mysqli_fetch_assoc($result_users);
              $password = $user['password'];
              
              
              
                $passwordtemp0 = $_POST['password_0'];
                $passwordtemp1 = $_POST['password_1'];
                $passwordtemp2 = $_POST['password_2'];
                
                $password0 = md5($passwordtemp0);
                $password1 = md5($passwordtemp1);
                $password2 = md5($passwordtemp2);

                
        
                
                $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                if ($passwordtemp0 == "") {
              	    array_push($errors, "Current password required.") ;
              	    
                }
            
                if ($passwordtemp1 == "") {
              	    array_push($errors, "New password required.");
              	    
                }
                if ($passwordtemp2 == "") {
              	    array_push($errors, "Repeat new password is required");
              	
                }
                if (!($passwordtemp1 == $passwordtemp2)) {
                    array_push($errors, "Repeat new password must match new password.");
             
                }
                if (!($password == $password0)) {
                 
                    array_push($errors, "Current password incorrect");
                   
                }
                
                if (strlen($passwordtemp1) <= '8') {
                    array_push($errors, "Your Password Must Contain At Least 8 Characters!");
                }
                
                if(!preg_match("#[0-9]+#",$passwordtemp1)) {
                    array_push($errors, "Your Password Must Contain At Least 1 Number!");
                }
                
                if(!preg_match('/[^a-zA-Z\d]/', $passwordtemp1)) {
                    array_push($errors, "Your Password Must Contain At Least 1 Special Character!");
                 
                }
                
             
                if (count($errors) == 0) {
                    $email = $user['email'];
                    $six_digit_random_number = mt_rand(100000, 999999);
                    $subject = "Post17 One Time Password";
                    $message = "Your one time password: \n$six_digit_random_number";
                    

                    
                    $retval = mail($email, $subject, $message);
                    $_SESSION['newpass'] = $password1;
                    $_SESSION['otp'] = $six_digit_random_number;
                    
                    echo "<br><h5>One time password sent to your email: $email </h5>";
                    echo "<form method=\"post\">
                                <label >Enter the one time password: </label>
                                <input type=\"text\" name=\"OTP\">
                                <button name=\"verify\" class=\"btn btn-primary btn-sm\">Verify</button>
                            </form><br>";
                }
        }
        if (isset($_POST['verify'])) {
                $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
            
            $username = $_SESSION['username'];
            $otp = $_POST['OTP'];
            $check = $_SESSION['otp'];
            $newpass = $_SESSION['newpass'];
            $otpstring = "$check";
            if ($otp == $otpstring) {
                

                               
                  	$sql = "UPDATE Users SET password='$newpass' WHERE username='$username'";
                  	
                  	mysqli_query($conn, $sql);
                  	
                  	if ($conn->query($sql) === TRUE) {
                  	    unset($_SESSION['password']);
                      	$_SESSION['password'] = $password1;
                        $succesfullMessage = "Successfully changed password!";
                        $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
                        echo $messageecho;
                    } else {
                      echo "Error changing password: " . $conn->error;
                    }
            } else {
                echo "incorrect one time password.";
            }
        }
    ?>
    <?php include('errors.php'); ?>
    </div>
    </div>
    <?php include "includes/notifications.php" ?>
    </div>
    </div>
    <?php include "includes/footer.php" ?>


</body>
</html>
