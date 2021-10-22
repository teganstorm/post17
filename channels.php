<?php 
  session_start(); 
 
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	unset($_SESSION['previous_location']);
  	unset($_SESSION['page']);
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
<body style="background: transparent">
    <?php include "includes/channelsheader.html" ?>
    <?php include "includes/carousel.html" ?>
    
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
        <?php
         /*echo "<br><form method=\"post\" action=\"$previous_location\">
                    <input type=\"submit\" value=\"back\" name=\"back\" >
                    </form>";*/
            ?>
        <!-- title bar -->
        <br>
        <div class = "row row-cols-3">
            <div class = "col-lg-3 col-md-4 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Channels</h2>
             </div></div>
            </div>
            <div class = "col-lg-5 col-md-4">
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
    <div class="border border-0 rounded-3  mb-5 bg-body">
        <div class = "card border-dark border-2 shadow-lg">
            <div class = "card body border-0">
                <ul class="list-group list-group-flush border-0">
                    <li class="list-group-item border-0">
        
        <p>To upload a channel, go to 'Create New Post' and select a channel from the dropdown menu for 'Add Post to Channel'</p>
            <h5>Create a channel:</h5>
        
        
        <?php
        
                $username = $_SESSION['username'];
                echo "<input type = \"button\" class=\"btn btn-primary btn-sm\" id=\"newchannel\" onclick=\"location.href='createchannel.php?username=$username'\"value = \"Create a Channel\" />";
            echo '</li>';
            echo '<li class="list-group-item border-0">';
                echo "<h5>My Channels: </h5>";
                echo "<input type = \"button\" class=\"btn btn-primary btn-sm\" id=\"newchannel\" onclick=\"location.href='mychannels.php?'\" value = \"My Channels\"/><br><br>";
                
                echo "<h5>Joined Channels: </h5>";            
                echo "<input type = \"button\" class=\"btn btn-primary btn-sm\" id=\"newchannel\" onclick=\"location.href='joinedchannels.php?'\" value = \"Joined Channels\" /><br><br>";
                
                echo "<h5>Recommended Channels: </h5>";       
                echo "<input type = \"button\" class=\"btn btn-primary btn-sm\" id=\"newchannel\" onclick=\"location.href='recomendedchannels.php?'\" value = \"Recommended Channels\" />";
            echo '</li><br>';
            ?>
            </ul>
    </div>
    </div>
    </div>
    </div>
         <?php include "includes/notifications.php" ?>
    </div>
</div>
    <?php include "includes/footer.php" ?>
<br>
<br>
<br>
</body>
</html>

