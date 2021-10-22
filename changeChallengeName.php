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
  
  $ypos = $_COOKIE["ypos"];
?>

<?php

        $errors = array(); 
        if (isset($_POST['changechallengename'])) {
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $challenge_id = $_GET['challenge'];
                        
            $query12 = "SELECT * From Challenge WHERE challengeID='$challenge_id'";
            $result = mysqli_query($conn, $query12);
            $challenge = mysqli_fetch_assoc($result);
            $challengeName = $challenge['challengeName'];
            $challengeName1 = $_POST['challengename1'];
            $challengeName2 = $_POST['challengename2'];
        
            $i = 0;

            
            if (empty($challengeName1)) {
      	        array_push($errors ,"Please enter a challenge name!");
            }
            if (empty($challengeName2)) {
      	        array_push($errors ,"Repeat challenge name is required!");
            }
        
            if (!($challengeName1 == $challengeName2)) {
               array_push($errors ,"Repeat challenge name must match challenge name!");
            }
        
            $challengename_check_query = "SELECT * FROM Challenge WHERE challengeName ='$challengeName1' ";
            $result = mysqli_query($conn, $challengename_check_query);
            $cname = mysqli_fetch_assoc($result);
        
            if ($cname) { 
                if ($cname['challengeName'] == $challengeName1) {
                    array_push($errors ,"Challenge name already exists, please enter a new challenge name");
                }
            }
            include('errors.php');
            if (count($errors) == 0) {
          	    $sql = "UPDATE Challenge SET challengeName='$challengeName1' WHERE challengeName='$challengeName'";
          	    mysqli_query($conn, $sql);
          	    
          	    $succesfullMessage = "Successfully changed channel name!";
                $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
          	

        }
        }
    
        ?>

<!-- VIEW CHANNEL PAGE -->
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
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html"?>
    
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
    
    <div class="container">
        <br>
        <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-4 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Change Name</h2>
             </div></div>
            </div>
            <div class = "col-lg-4 col-md-4">
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
          
    <div class="col-md-8 col-12 d-block">
    <div class="border border-0 rounded-3 mb-5 bg-transparent">
            
        <div class = "card border-dark border-2">
        <div class = "card body border-0">
            <ul class="list-group list-group-flush">
            
            <li class="list-group-item border-0">
                <form method="post">
                    <?php        echo $messageecho; ?>
                    <div class="input-group">
                        <label id="newchallengtext">Enter your new challenge name:&nbsp;&nbsp;</label>
                        <input id="challengename" type="text" name="challengename1">
                    </div>
                
                    <div class="input-group">
                        <label id="renewchallengetext">Re-enter your new challenge name:&nbsp;&nbsp;</label>
                        <input id="rechallengename" type="text" name="challengename2">
                    </div>
                    <br>
                    <div class="input-group">
                        <button id="confirm" type="submit" name="changechallengename" class="btn btn-primary btn-sm" onclick= "confirm('Are you sure you want to change your challenge name?')">Set New Challenge Name</button>
                    </div>
                                     

                </form>
            </li>
        </div>
        </div>
        

            
    </div>
    </div>
         <?php include "includes/notifications.php" ?>
    </div>
    </div>
<br>
<br>
<br>
    <?php include "includes/footer.php" ?>
</body>
</html>