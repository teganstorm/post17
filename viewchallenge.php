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
    
<!-- VIEW CHALLENGE PAGE -->
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
    <?php include "includes/carousel.html"?>
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container">
         <br>
         <div class = "row row-cols-3">
            <div class = "col-lg-3 col-md-5 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>View Challenge</h2>
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
        
      <div class="row">
        <div class="col-md-8 col-12 d-block">
        <div class="border border-0 rounded-3 mb-5 bg-transparent">
    
    <?php
        echo '<div class = "card border-2 border-dark shadow-lg">';
        echo '<div class = "card body border-0">';
        echo '<ul class="list-group list-group-flush">';
        echo '<li class="list-group-item border-0">';
        
        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
                  die("Connection failed: " . $db->connect_error);
        }
        $username = $_SESSION['username'];
        $query = "SELECT * FROM Users WHERE username = '$username' ";
        $results = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($results);
        $id = $user['userID'];
    
        $challenge_id = $_GET['challenge'];
        $query = "SELECT * From Challenge WHERE challengeID='$challenge_id'";
        $result = mysqli_query($db, $query);
        $challenge = mysqli_fetch_assoc($result);
        $name = $challenge['challengeName'];
        $sdate = $challenge['startDate'];
        $edate = $challenge['endDate'];
        $description = $challenge['description'];
        
            $currDate1 = date("Y-m-d"); 
            $currDate1 = new DateTime($currDate1); // convert into seconds
            $startdate1 = new DateTime($sdate); // convert the start date specified by the user into seconds
            $enddate1 = new DateTime($edate); // convert the start date specified by the user into seconds*/
            

        
                
        // if user not already in challenge
        $query = "SELECT * From Participants WHERE challengeID='$challenge_id' and userID='$id'";
        $results = mysqli_query($db, $query);
        $participant = mysqli_fetch_assoc($results);
        
        
        
            if ($currDate1 >= $enddate1) {
                echo "<br><h5 style=\"color:coral\">This challenge has expired.</h5>";  
            } else {
                echo "To upload a participating post to this challenge, go to 'Create New Post' and select this challenge from the dropdown menu for 'Participate in Challenge'.<br>";
                if ($participant) {
                    echo "<br><h5 style=\"color:coral\">You have already joined this challenge.</h5>";            
                } else {
                    echo "<br><form method=\"post\">
                                <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Join Challenge\" name=\"join_challenge\">
                            </form><br>";
                }
            }
        

        echo "<h5>Challenge Name: $name<br></h5>";
        echo "<h5>Start Date: $sdate<br></h5>";
        echo "<h5>End Date: $edate <br></h5>";
        echo "<h5>Challenge Description: $description<br></h5>";
        
        $tmp_challenge_winnerID = $challenge['winnerUserID'];
        
            if ($currDate1 >= $enddate1) {
                // get challenge winner
                $q = "SELECT * FROM Users WHERE userID='$tmp_challenge_winnerID'";
                $r = mysqli_query($db, $q);
                echo "<h5 style=\"color:coral\">Winner(s): </h5>";
                while ($u = mysqli_fetch_array($r)) {
                    $username_winner = $u['username'];
                    echo "<h5 style=\"color:coral\"><a href='youraccount.php?view_username=$username_winner'; style='color:#20b2aa';  \">@$username_winner</a><br></h5>";
                }
            }
        echo "The post with the most likes on the challenge end date wins the challenge.<br>";
        echo '</li></ul></div></div><br><br>';
        
        
        $q = "SELECT * FROM Posts WHERE challengeID='$challenge_id' ORDER BY likes DESC";
        $results = mysqli_query($db, $q);
        $posts_count = 0;
        while ($row = mysqli_fetch_array($results)) {
            echo '<div class = "card border-2 border-dark shadow-lg">';
            echo '<div class = "card body border-0 mt-2">';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item border-0">';
            $posts_count++;
            $post_src = $row['imgSrc'];
            $post_likes = $row['likes'];
            $post_id = $row['postID'];
            echo "<img src=\"$post_src\" style=\"width:100%; border:2px solid black;\" class=\"card-img-top\"><br>";
            echo "Total <img src=\"images/like.png\" style=\"width:3%\">s: $post_likes <br>";
                            echo "
                                <form method=\"post\" action='viewpost.php?post=$post_id'>
                                    <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View post\" name=\"viewpost\">
                                </form>
                            ";
            echo '</li></ul></div></div><br><br>';
        }
        if ($posts_count == 0) {
            echo '<div class = "card border-2 border-dark shadow-lg">';
            echo '<div class = "card body border-0">';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item border-0">';
            echo "There are no participating posts in this challenge yet.<br>";
            echo '</li></ul></div></div>';
        }
        
        
        if (isset($_POST['join_challenge'])) {
            $query = "INSERT INTO Participants (challengeID, userID) VALUES ('$challenge_id','$id')";
            mysqli_query($db, $query);
            unset($POST['join_challenge']);;
            
        }


                

    
    ?>
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