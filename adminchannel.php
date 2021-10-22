<?php 
  session_start(); 
  header("Cache-Control: max-age=300, must-revalidate"); 

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
    <link rel="stylesheet" href="styles/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body style="background: transparent">
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

    
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container">
         <br>

         <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-6 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Channel Settings</h2>
             </div></div>
            </div>
            <div class = "col-lg-4 col-md-2">
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
    
        echo '<div class = "card border-dark border-2 shadow-lg ">';
        echo '<div class = "card body border-0">';
        echo '<ul class="list-group list-group-flush">';
        echo '<li class="list-group-item border-0">';
    $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
                  die("Connection failed: " . $db->connect_error);
        }
        $errors = array(); 
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
        $public = $channel['public'];
        
        echo "<h5>Channel Name: $name<br></h5>";
        
        echo"<form method=\"post\" action='changeChannelName.php?channel=$channel_id' >
             <input type=\"submit\" value=\"Change Name\" name=\"changename\" class=\"btn btn-primary btn-sm\">
             </form><br> ";
             
        echo "<h5>Channel Description: $description<br></h5>";
        echo "<form method=\"post\"action='changeChannelDesc.php?channel=$channel_id' >
             <input type=\"submit\" value=\"Change Description\" name=\"changedesc\" class=\"btn btn-primary btn-sm\">
             </form><br> ";
             
             
        // invite a user you are following to this channel
        echo "<h5>Invite User: <br></h5>";
        echo "Invite a user which you are following to join this channel.";
        

        
        
                        // but not actually so simple we dont want users who are already in the channel...
                $getFollowing = 
                        "SELECT * FROM Followers 
                        JOIN (SELECT DISTINCT userID FROM usersInChannel 
                                WHERE channelID != $channel_id) AS UIC
                        ON Followers.userID = UIC.userID
                        WHERE followedBy='$id'";
                $re= mysqli_query($db, $getFollowing);
                $following_ids = array();
                $following_index = 0;
                while ($row = mysqli_fetch_array($re)) {
                    $temp_ID = $row['userID'];
                    $following_ids[$following_index] = $temp_ID;
                    $following_index++;
                }
                
                
                
                $usernames = array();
                $id_array_size = count($following_ids);
                $index = 0;
                for($index; $index < $id_array_size; $index++) {
                    $tmp_id = $following_ids[$index];
                    $name = "SELECT * FROM Users WHERE userID='$tmp_id' ";
                    $result_name = mysqli_query($db,$name); 
                    $tmp_name1 = mysqli_fetch_assoc($result_name);
                    $tmp_name2 = $tmp_name1['username'];
                    $usernames[$index] = $tmp_name2;
                }
                $index = 0;
                $size = count($usernames);
                
            echo "<form method=\"post\">";
                
                echo "<select name = 'username'>";
                
                echo "<option value = '" . "Select" ."' >" ."Select" . "</option>";
                
                for($index; $index < $size; $index++) {
                    echo "<option value = '" . $usernames[$index] ."' >" .$usernames[$index] . "</option>";
                }
                echo "</select><br>";
                
                echo "<input id=\"submit\" type=\"submit\" name=\"invite\" value=\"Invite User\" class=\"btn btn-primary btn-sm\")/>";
                
            echo "</form><br>";
            
        if (isset($_POST['invite'])) {
            $inviteUser = $_POST['username'];
            if ($inviteUser == "Select") {
                array_push($errors ,"Please select a valid user to invite!");
            }
            include('errors.php');
            if (count($errors) == 0) {
            $name = "SELECT * FROM Users WHERE username='$inviteUser'";
            $result_name = mysqli_query($db,$name); 
            $tmp_name1 = mysqli_fetch_assoc($result_name);
            $tmp_name2 = $tmp_name1['userID'];

            $sql100 = "INSERT INTO channel_invite (channelID, userID) VALUES ('$channel_id' , '$tmp_name2')";
            mysqli_query($db, $sql100);
                            
            $succesfullMessage = "Successfully invited user!";
            $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
            echo $messageecho; 
            
            unset($_POST['invite']);
              }
        }
        
        echo "<h5>Change Privacy:</h5>";
        if ($public == 1) {
            echo "This channel is currently a public channel.<br>";
            echo "Private channels are invite only, and only members in the channel and members which you have sent invites to will be able to see the posts in the private channel.<br>";
            echo "<form method=\"post\">
                    <input type=\"submit\" value=\"Change To Private\" name=\"private\" class=\"btn btn-primary btn-sm\">
                 </form><br> ";
        } else {
            echo "This channel is currently a private channel.<br>";
            echo "Private channels are invite only, and only members in the channel and members which you have sent invites to will be able to see the posts in the private channel.<br>";
            echo "<form method=\"post\">
                    <input type=\"submit\" value=\"Change To Public\" name=\"public\" class=\"btn btn-primary btn-sm\">
                 </form><br> ";
        }
        
        if (isset($_POST['public'])) {
            $q = "UPDATE Channel SET public='1' WHERE channelID='$channel_id'";
            mysqli_query($db,$q); 
            unset($_POST['public']);
            echo("<meta http-equiv='refresh' content='1'>");
            
            
        }
        
        if (isset($_POST['private'])) {
            $q = "UPDATE Channel SET public='0' WHERE channelID='$channel_id'";
            mysqli_query($db,$q); 
            unset($_POST['private']);
            echo("<meta http-equiv='refresh' content='1'>");
            
        }
        

                
    

        echo 
            "<form method=\"post\">
            
            <input type=\"submit\" 
             
            value=\"Delete Channel\" 
            name=\"delete\" 
            onclick=\"return confirm('Are you sure you want to delete this channel?')\" class=\"btn btn-danger btn-sm\"></form>";


        
      

        
         echo "</li></ul></div></div><br><br>";
            
        $q = "SELECT * FROM postsInChannel WHERE channelID='$channel_id'";
        $results = mysqli_query($db, $q);
        $posts_count = 0;
        $post_ids = array();
        
        
        
        while ($row = mysqli_fetch_array($results)) {
            echo '<div class = "card border-dark border-2 shadow-lg ">';
            echo '<div class = "card body border-0 mt-2">';
            echo '<ul class="list-group list-group-flush">';
            $temp_postid = $row['postID'];
            $post_ids[$posts_count] = $temp_postid;
            $post_id = $row['postID'];
            $sql = "SELECT * FROM Posts WHERE postID='$post_id'";
            $res = mysqli_query($db, $sql);
            $post = mysqli_fetch_assoc($res);
            
            $post_src = $post['imgSrc'];
            echo '<li class="list-group-item border-0">';
            echo "<img src=\"$post_src\" style=\"width:100%\"><br>";
            echo '</li>';
            // check if this post has been reported and if it has display the reason for report
            $q2 = "SELECT * FROM Report WHERE postID='$post_id'";
            $r = mysqli_query($db, $q2);
            while ($report = mysqli_fetch_array($r)) {
                echo '<li class="list-group-item border-0">';
                $reason = $report['comment'];
                echo "<a style=\"font-weight:bold; color:coral\">     This post has been reported, reason for reporting: \"$reason\"<br></a>";
                echo '</li>';
            }
            echo '<li class="list-group-item border-0">';
            echo "<form method=\"post\" action='viewpost.php?channel=$channel_id'>
                        <input type=\"submit\" value=\"View post\" name=\"viewpost\" class=\"btn btn-primary btn-sm\">
                  </form>";
            echo '</li>';
            echo '<li class="list-group-item border-0">';
                            echo 
                            "<form method=\"post\">
                            <input type=\"submit\" 
                             
                            value=\"Remove\" 
                            name=\"remove$posts_count\" 
                            onclick=\"return confirm('Are you sure you want to remove this post?' )\" class=\"btn btn-danger btn-sm\"></form>";
    $posts_count++;
           echo "</li></ul></div></div><br><br>";                
        }

        
         $index = 0;

         for ($index; $index < $posts_count; $index++) {
            $temp = "remove$index";
            if(isset($_POST[$temp])) {
                $remove_postid = $post_ids[$index];

                // remove post from channel table
                $query1 = "DELETE FROM postsInChannel WHERE postID ='$remove_postid' ";
                mysqli_query($db,$query1);
    
                echo("<meta http-equiv='refresh' content='1'>");
            
            }
            
         }
         
         
         
        $delete = "delete";
        if(isset($_POST[$delete])) {
            $index = 0;
            for ($index; $index < $posts_count; $index++) {
            $remove_postid = $post_ids[$index];

            // remove post from channel table
            $query1 = "DELETE FROM postsInChannel WHERE postID ='$remove_postid' ";
            mysqli_query($db,$query1);

            echo("<meta http-equiv='refresh' content='1'>");
            }
        
        
            $removeChannelQuery = "DELETE FROM Channel WHERE channelID = '$channel_id' ";

            mysqli_query($db,$removeChannelQuery);

            
            $removeUsersInChannelsQuery = "DELETE FROM usersInChannel WHERE channelID = '$channel_id'";
            mysqli_query($db,$removeUsersInChannelsQuery);

        echo '<script>location.href="https://post17.net/channels.php"</script>';
        }
        
        if ($posts_count == 0) {
            echo '<div class = "card border-dark border-2 shadow-lg ">';
                echo '<div class = "card body border-0 mt-1">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item  border-0 mb-1">';
            echo "There are no posts in this channel yet.<br>";
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