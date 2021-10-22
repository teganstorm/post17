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
    if (isset($_POST['delete'])) {
            // Create connection
            $username = $_SESSION['username'];
            
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
            
            // get current userID
            $userIDQuery = "SELECT * FROM Users WHERE username = '$username' ";
            $results = mysqli_query($conn, $userIDQuery);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            
            // get all the challenges created by this user
            $challengeidget = "SELECT * FROM Challenge WHERE userID = '$id'"; 
            $results2 = mysqli_query($conn, $challengeidget);
   
            // need to store all these challengeID's in an array 
            
            $challenge_ids = array();
            $challenge_count = 0;
            

            while ($row = mysqli_fetch_array($results2)) {
                $temp_challengeid = $row['challengeID'];
                $challenge_ids[$challenge_count] = $temp_challengeid;
                $challenge_count++;
            }
            
            $index = 0;
            $participantsRemove2 = "DELETE FROM Participants WHERE userID='$id'";
            mysqli_query ($conn,$participantsRemove2);
            // unbind all the posts anyone has to the users challenge 
            for ($index; $index < $challenge_count; $index++) {
                $remove_challengeid = $challenge_ids[$index];
                $query1 = "UPDATE Posts SET challengeID = $null_ WHERE challengeID ='$remove_challengeid' ";
                mysqli_query ($conn,$query1);
                $participantsRemove = "DELETE FROM Participants WHERE challengeID='$remove_challengeid'";
                mysqli_query ($conn,$participantsRemove);
                $challenge_winnersRemove = "DELETE FROM challenge_winners WHERE challengeID = '$remove_challengeid'";
                mysqli_query ($conn,$challenge_winnersRemove);
            }
            
            $challenge_winnersRemove = "DELETE FROM challenge_winners WHERE userID = '$id'";
            mysqli_query($conn, $challenge_winnersRemove);

 
            // remove all the challenges created by the user
            $challengeRemove = "DELETE FROM Challenge WHERE userID='$id'";
            mysqli_query($conn, $challengeRemove);
     
            // remove all invites sent to the user
            $channelinv_remove = "DELETE FROM channel_invite WHERE userID = '$id'";
            mysqli_query($conn, $channelinv_remove);
 
            
            $channel_ids = array();
            $channel_count = 0;
            
            $get_channels = "SELECT * FROM Channel WHERE userID = '$id'";
            
            $results3 = mysqli_query($conn, $get_channels);
            $cID = mysqli_fetch_assoc($results3);
            
                        
            while ($row = mysqli_fetch_array($results3)) {
                $temp_channelid = $row['channelID'];
                $channel_ids[$channel_count] = $temp_channelid;
                $channel_count++;
            }
            
            // remove all users in channels created by deleted user.
            // remove invites sent from all channels created from the deleting user
            // remove all the posts in channels created by deleted user.
            $usersInChannelsRemove2 = "DELETE FROM usersInChannel WHERE userID='$id'";
            mysqli_query ($conn,$usersInChannelsRemove2);
            for ($index; $index < $channel_count; $index++) {
                $remove_channelid = $channel_ids[$index];
                $usersInChannelsRemove = "DELETE FROM usersInChannel WHERE channelID='$remove_channelid'";
                mysqli_query ($conn,$usersInChannelsRemove);
                $channelinv_remove = "DELETE FROM channel_invite WHERE channelID = '$remove_channelid'";
                mysqli_query ($conn,$channelinv_remove);
                $channelposts_remove = "DELETE FROM postsInChannel WHERE channelID = '$remove_channelid'";
                 mysqli_query ($conn,$channelposts_remove);
            }
            
            // remove everyone that the deleted user followed
            $followers_remove = "DELETE FROM Followers WHERE userID = '$id'";
            mysqli_query($conn, $followers_remove);
     
            
            // remove everyone that the deleted user was followed by
            $followers2_remove = "DELETE FROM Followers WHERE followedBy = '$id'";
            mysqli_query($conn, $followers2_remove);
       
            // remove channels created by the deleted user
            $channel_remove = "DELETE FROM Channel WHERE userID = '$id'";
            mysqli_query($conn, $channel_remove);

            
            // remove ratings from all posts created by a user.
            $rating_remove = "DELETE FROM Rating WHERE userID = '$id'";
            mysqli_query($conn, $channel_remove);

            
            // remove all the ratings from posts that the deleted user created
            $post_ids = array();
            $post_count = 0;
            $index = 0;
            
            $get_posts = "SELECT * FROM Posts WHERE userID = '$id'";
            
            $results4 = mysqli_query($conn, $get_posts);
            
            
            while ($row = mysqli_fetch_array($results4)) {
                $temp_postID = $row['postID'];
                $post_ids[$post_count] = $temp_postID;
                $post_count++;
            }
            
            // remove all the ratings on any posts the deleted user may have posted
            // remove all the reports made on any of the deleted users posts
            // remove all the savedposts the deleted user created
            // remove any tags from posts the deleted user created
            // 
            $savedPosts2 = "DELETE FROM savedPosts WHERE userID = '$id'";
            mysqli_query($conn, $savedPosts2);
            for ($index; $index < $post_count; $index++) {
                $remove_postID = $post_ids[$index];
                $removeRatingQuery = "DELETE FROM Rating WHERE postID = '$remove_postID'";
                mysqli_query($conn, $removeRatingQuery);
                $reportQuery = "DELETE FROM Report WHERE postID = '$remove_postID'";
                mysqli_query($conn, $reportQuery); 
                $savedPosts = "DELETE FROM savedPosts WHERE postID = '$remove_postID'";
                mysqli_query($conn, $savedPosts);
                $removeTags = "DELETE FROM Tags WHERE postID = '$remove_postID'";
                mysqli_query($conn, $removeTags);
            }
            
            // remove any ratings the user gave to anyone else
            $removeRatingUserQuery = "DELETE FROM Rating WHERE userID = '$id'";
            
            mysqli_query($conn, $removeRatingUserQuery);
            
            // remove any reports the deleted user may have made
            $reportQuery = "DELETE FROM Report WHERE userID = '$id'";
            mysqli_query($conn, $reportQuery);

            
            // remove all comments posted by the user
            $comment_remove = "DELETE FROM Comments WHERE userID = '$id'";
            mysqli_query($conn, $comment_remove);

            
            $index = 0;
            for ($index; $index < $post_count; $index++) {
                $remove_postID = $post_ids[$index];
                $removePost = "DELETE FROM Posts WHERE postID = '$remove_postID'";
                mysqli_query($conn, $removePost);
            }
            
 

            // delete user from database
            $sql = "DELETE FROM Users WHERE userID='$id'";
            mysqli_query($conn, $sql);
            

 	       unset($_SESSION['Ausername']);
 	
 	       echo '<script>location.href="https://post17.net/login.php"</script>';


  }
?>
<!-- ACCOUNT SETTINGS PAGE
    accessible from click button on my account page-->
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
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>

    <div class="container">
        <br>
    
       <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-5 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Account Settings</h2>
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
            <form method="post" action="changeusername.php">
                <h5>Change Username:</h5>
                <p>Would you like to change your username?</p>
            <button class="btn btn-primary btn-sm" id="changeusername">Change Username</button>
            </form>
        </li>
    
        <li class="list-group-item border-0">
            <form method="post" action="changepassword.php">
                <h5>Change Password:</h5>
                <p>Would you like to change your password?</p>
            <button class="btn btn-primary btn-sm" id="changepassword">Change Password</button>
            </form>
        </li>
    
        <li class="list-group-item border-0">
            <form method="post">
                <h5>Delete Account:</h5>
                <p>Would you like to delete your account?</p>
                <button id="deleteaccount" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete your account? You cannot undo this aciton.')" name="delete">Delete Account</button>
            </form>
        </li>
    
    <!-- check if notifications are on -->
    <li class="list-group-item border-0">
    <?php
        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
        $username = $_SESSION['username'];
        
        
          $query_userid = "SELECT * FROM Users WHERE username='$username'";
          $result_users = mysqli_query($conn, $query_userid);
          $user = mysqli_fetch_assoc($result_users);
          $id = $user['userID'];
          $notif = $user['notifications'];
    
        if ($notif == 1) {
        echo "
        <form method=\"post\">
            <h5>Notifications Settings:</h5>
            <p>Notifications are currently on.<br>Would you like to turn off notifications?</p>
            <button id=\"turnpffnotifications\" class=\"btn btn-primary btn-sm\" name=\"notifications\" onclick=\"return confirm('Are you sure you want to turn off notifications?')\">Turn off Notifications</button>
            <br> ";
                    if (isset($_POST['notifications'])) {
                        // Create connection
                        $username = $_SESSION['username'];
                        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                        // Check connection
                        if ($conn->connect_error) {
                          die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // sql to delete a record
                        $sql = "UPDATE Users SET notifications=0 WHERE username='$username'";
                        mysqli_query($conn, $sql);
                        if ($conn->query($sql) === TRUE) {
                          echo "Notifications have been turned off.";
                        } else {
                          echo "Error chnaging record: " . $conn->error;
                        }
                        unset($_POST['notifications']);
                        echo("<meta http-equiv='refresh' content='1'>");
                    }
        echo "
        </form><br>
        ";
        } else {
            echo "
            <form method=\"post\">
            <p>Notifications are currently switched off.<br>Would you like to turn on notifications?</p>
            <button id=\"turnpffnotifications\" class=\"btn btn-primary btn-sm\" name=\"notificationsOn\" onclick=\"return confirm('Are you sure you want to turn on notifications?')\">Turn on Notifications</button>
            <br> ";
                    if (isset($_POST['notificationsOn'])) {
                        // Create connection
                        $username = $_SESSION['username'];
                        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                        // Check connection
                        if ($conn->connect_error) {
                          die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // sql to delete a record
                        $sql = "UPDATE Users SET notifications=1 WHERE username='$username'";
                        mysqli_query($conn, $sql);
                        if ($conn->query($sql) === TRUE) {
                          echo "Notifications have been turned on.";
                        } else {
                          echo "Error chnaging record: " . $conn->error;
                        }
                        unset($_POST['notifications']);
                        echo("<meta http-equiv='refresh' content='1'>");
                    }
        echo "
        </form><br>
        ";
        }
        echo "<h5>Change Privacy:</h5>";
        $username = $_SESSION['username'];
        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
          $query_userid = "SELECT * FROM Users WHERE username='$username'";
          $result_users = mysqli_query($conn, $query_userid);
          $user = mysqli_fetch_assoc($result_users);
          $public = $user['public'];
          $id = $user['userID'];
        
        
        if ($public == 1) {
            echo "Your Account is currently Public.<br>";
            echo "Anyone can view posts by a public account. If you switch to private then users will need to request to follow you and you must except before they can see your posts.<br>";
            echo "<form method=\"post\">
                    <button name=\"private\" class=\"btn btn-primary btn-sm\">Change to Private</button>
                 </form><br> ";
        } else {
            echo "This Account is currently Private.<br>";
            echo "Anyone can view posts by a public account. At the moment, users need to request to follow you and you must except before they can see your posts.<br>";
            echo "<form method=\"post\">
                    <button name=\"public\" class=\"btn btn-primary btn-sm\">Change To Public</button>
                 </form><br> ";
        }
        if (isset($_POST['public'])) {
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            $q = "UPDATE Users SET public='1' WHERE userID='$id'";
            if ($conn->query($q) === TRUE) {
            } else {
                echo "<br><br><br><br>it didn't work" . $sql . "<br>" . $conn->error;
            }
            unset($_POST['public']);
            echo("<meta http-equiv='refresh' content='1'>");
            
            
        }
        
        if (isset($_POST['private'])) {
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            $q = "UPDATE Users SET public='0' WHERE userID='$id'";
            mysqli_query($conn,$q); 
            unset($_POST['private']);
            echo("<meta http-equiv='refresh' content='1'>");
            
        }
    ?>
    </li>
    </div>
    </div>
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
