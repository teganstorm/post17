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
    
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container">
         <br>
         
         <div class = "row row-cols-3">
            <div class = "col-lg-3 col-md-5 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>View Channel</h2>
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
    
        $channel_id = $_GET['channel'];
        $query = "SELECT * From Channel WHERE channelID='$channel_id'";
        $result = mysqli_query($db, $query);
        $channel = mysqli_fetch_assoc($result);
        $name = $channel['channelName'];
        $creatorID = $channel['userID'];
        $description = $channel['description'];
        
        
        // check if the channel is private and if it is check if there is an invite for this user for this channel or if this user is a member of this channel
        
        // if channel is public 
        $privacy = $channel['public'];
        
        // or if the user in the channel
        $q = "SELECT * From usersInChannel WHERE channelID='$channel_id' AND userID='$id'";
        $result = mysqli_query($db, $q);
        $userinchannel = mysqli_fetch_assoc($result);
        
        // or if the user has an invite to the channel
        $q = "SELECT * From channel_invite WHERE channelID='$channel_id' AND userID='$id'";
        $result = mysqli_query($db, $q);
        $channelinvite = mysqli_fetch_assoc($result);
        
        if (($privacy == 1)|| ($userinchannel) || ($channelinvite)) {
            
            // if user not already in challenge
            $query = "SELECT * From usersInChannel WHERE channelID='$channel_id' and userID='$id'";
            $results = mysqli_query($db, $query);
            $channelmember = mysqli_fetch_assoc($results);
            
                    echo "To upload a post to this channel, go to 'Create New Post' and select this channel from the dropdown menu for 'Add post to Channel'.<br>";
                    if ($channelmember) {
                        echo "<br><h5 style=\"color:coral\">You are a member of this channel.</h5><br>";            
                    } else {
                        echo "<br><form method=\"post\">
                                    <input type=\"submit\" value=\"Join Channel\" name=\"join_channel\" class=\"btn btn-primary btn-sm\">
                                </form><br>";
                    }
                     
                    $query = "SELECT * FROM Users WHERE userID = '$creatorID' ";
                    $results = mysqli_query($db, $query);
                    $user = mysqli_fetch_assoc($results);
                    $creator_username = ($user['username']);
            
    
            echo "<h5>Channel Name: $name<br></h5>";
                        echo "<h5>Created By: <a href = 'youraccount.php?view_username=$creator_username' style=\"color:#20b2aa\"> @$creator_username</a>  ";
                        
            
            echo "<h5>Channel Description: $description<br></h5>";
            
            echo '</li></ul></div></div><br><br>';
            
            
            
            $q = "SELECT * FROM postsInChannel WHERE channelID='$channel_id'";
            $results = mysqli_query($db, $q);
            $posts_count = 0;
            while ($row = mysqli_fetch_array($results)) {
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0 mt-2">';
                
                $posts_count++;
                
                $post_id = $row['postID'];
                $sql = "SELECT * FROM Posts WHERE postID='$post_id'";
                $res = mysqli_query($db, $sql);
                $post = mysqli_fetch_assoc($res);;
                $post_src = $post['imgSrc'];
                
                echo "<img src=\"$post_src\" style=\"width:100%; border:2px solid black;\" class=\"card-img-top\"><br><br>";
                                echo "
                                    <form method=\"post\" action='viewpost.php?post=$post_id'>
                                        <input type=\"submit\" value=\"View post\" name=\"viewpost\" class=\"btn btn-primary btn-sm\">
                                    </form>
                                ";
                                echo '</li></ul></div></div><br><br>';
            }
                if ($posts_count == 0) {
                    echo '<div class = "card border-2 border-dark shadow-lg">';
                    echo '<div class = "card body border-0">';
                    echo '<ul class="list-group list-group-flush">';
                    echo '<li class="list-group-item border-0">';
                    echo "There are no posts in this channel yet.<br>";
                    echo '</li></ul></div></div>';
                }
            
            
                if (isset($_POST['join_channel'])) {
                    $query = "INSERT INTO usersInChannel (channelID, userID) VALUES ('$channel_id','$id')";
                    mysqli_query($db, $query);
                    
                    
                    // check if there was an invite for this user for this channel and if there was dlelete it.
                    $query_channel_invite = "SELECT * FROM channel_invite WHERE userID='$id' AND channelID='$channel_id'";
                    $r = mysqli_query($db, $query_channel_invite);
                    $invite = mysqli_fetch_assoc($r);
                    
                    if ($invite) {
                        $q = "DELETE FROM channel_invite WHERE userID='$id' AND channelID='$channel_id'";
                        mysqli_query($db, $q);
                    }
                    
                    
                    unset($POST['join_channel']);;
                    echo("<meta http-equiv='refresh' content='1'>");
                }
            
            } else {
                
                echo "This is a private channel and you have not been invited to join this channel.<br><br>You cannot view private channels unless you have an invite to the channel by the channel creator.<br>";
                echo '</li></ul></div></div>';
                
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