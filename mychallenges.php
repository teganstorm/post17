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
<!-- My challenges PAGE -->
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
        <br>
        <div class = "row row-cols-3">
            <div class = "col-lg-3 col-md-5 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>My Challenges</h2>
             </div></div>
            </div>
            <div class = "col-lg-5 col-md-3">
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
    <div class="col-sm-8 col-12 d-block">
    <div class="border border-0 rounded-3 mb-5 bg-transparent">
        
        <?php
            $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }
            $username = $_SESSION['username'];
            $query = "SELECT * FROM Users WHERE username = '$username' ";
            $results = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            
            // show all the channels which this user has created
            $q = "SELECT * FROM Challenge WHERE userID='$id'";
            $r = mysqli_query($db, $q);
            $count = 0;
            while ($challenge = mysqli_fetch_array($r)) {
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0">';
                $count++;
                $challenge_id = $challenge['challengeID'];
                $name = $challenge['challengeName'];
                $description = $challenge['description'];
                
                echo "<h4>$name</h4>";
                // obvys creator is just username
                echo "Created By: <a href='youraccount.php?view_username=$username' style=\"color:#20b2aa\">@$username</a><br>";
                echo "Description: $description";
                echo '</li>';
                echo '<li class="list-group-item border-0">';
                // view channel button
                          echo "
                          <div style=\"float: left; width: 130px\"> 
                                <form method=\"post\" action='viewchallenge.php?challenge=$challenge_id' style='float: left;'>
                                    <input type=\"submit\" value=\"View Challenge\" name=\"viewchannel\" class=\"btn btn-primary btn-sm\">
                                </form>
                            </div>
                            <div style=\"float: left; width: 130px\"> 
                                <form method=\"post\" action='editChallenge.php?challenge=$challenge_id '>
                                    <input type=\"submit\" value=\"Challenge Settings\" name=\"challengesettings\" class=\"btn btn-primary btn-sm\">
                                </form><br>
                            </div>
                            ";
                echo '</li></ul></div></div>';
                echo '<br>';
                // you can not delete or unjoin channels you have created
            }
            if ($count == 0) {
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0">';
                echo "You have not made any channels yet.</li></ul></div></div>";
            }
            
        ?>
        
        
    </div>
    </div>
         <?php include "includes/notifications.php" ?>
    </div>
</div>
    <?php include "includes/footer.php" ?>
</body>
<br>
<br>
<br>
</html>