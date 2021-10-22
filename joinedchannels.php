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
<!-- Recomended Channels PAGE -->
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
               <h2>Joined Channels</h2>
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
    <div class="col-md-8 col-12 d-block">
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
            $q = "SELECT * FROM usersInChannel WHERE userID='$id'";
            $r = mysqli_query($db, $q);
            $channel_ids = array();
            $channel_ids_index = 0;
            while ($c = mysqli_fetch_array($r)) {
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0">';
                $channel_id = $c['channelID'];
                $channel_ids[$channel_ids_index] = $channel_id;
                
                
                
                
                    
                    // get the channel information
                    
                    $sqli = "SELECT * FROM Channel WHERE channelID='$channel_id'";
                    $ri = mysqli_query($db, $sqli);
                    $channel = mysqli_fetch_assoc($ri);
                    $name = $channel['channelName'];
                    $description = $channel['description'];
                    $userid = $channel['userID'];
                    $query = "SELECT * FROM Users WHERE userID = '$userid' ";
                    $results = mysqli_query($db, $query);
                    $user = mysqli_fetch_assoc($results);
                    $username2 = ($user['username']);
                    echo "<h4>$name</h4>";
                    echo "Created By: <a href=youraccount.php?view_username=$username2 style=\"color:#20b2aa\">@$username2</a><br>";
                    // viewuseraccount button
                    //echo "<input type=\"button\" onclick=\"location.href='youraccount.php?view_username=$username2';\" value=\"View User Account\" /><br>";
                    echo "Description: $description<br><br>";
                    // join channel
                    
                    $q2 = "SELECT * FROM Channel WHERE userID='$id' AND channelID='$channel_id'";
                    $res = mysqli_query($db, $q2);
                    $channelcreator = mysqli_fetch_assoc($res);  
                    echo '<span class="d-block ">';
                    if (!$channelcreator) {
                        echo "
                            <div style=\"float: left; width: 130px\"> 
                            <form method=\"post\" style='float: left;'>
                                <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Unjoin Channel\" name=\"unjoinchannel$channel_id\">
                            </form>
                            </div>
                        ";
                    } else {
                        echo "<h5 style=\"color:coral\">You are the creator of this channel. (You cannot unjoin this channel.)</h5>";  
                    }
                          echo "
                          <div style=\"float: left; width: 130px\"> 
                                <form method=\"post\" action='viewchannel.php?channel=$channel_id'>
                                    <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View Channel\" name=\"viewchannel\">
                                </form>
                            </div>
                            ";
                            echo '<br>';
                    $channel_ids_index++;
                    echo '</span></li></ul></div></div><br><br>';
            }
            if ($channel_ids_index == 0) {
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0">';
                echo "You have not joined any channels yet.<br></li></ul></div></div>";
            }
            
            // loop through the channels in the array to check the button for join
            for ($i=0; $i<=$channel_ids_index; $i++) {
                $temp_channel_id = $channel_ids[$i];
                $tempname = "unjoinchannel$temp_channel_id";
                if (isset($_POST[$tempname])) {
                    // if they were already joined it would not have shown on recomended so we know they not already in this channel
                    // userid still = $id
                    $query = "DELETE FROM usersInChannel WHERE userID='$id' AND channelID='$temp_channel_id'";
                    mysqli_query($db, $query);
                    
                    
                    unset($_POST[$tempname]);
                    echo("<meta http-equiv='refresh' content='1'>");
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
<br>
<br>
<br>
</html>