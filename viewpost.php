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

  
  $ypos = $_COOKIE["ypos"];

if (isset($_POST['like'])) {
    
            $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            if ($db->connect_error) {
                      die("Connection failed: " . $db->connect_error);
            }
            
            $username = $_SESSION['username'];
            echo "username: $username";
            $query = "SELECT * FROM Users WHERE username = '$username'";
            $results = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            $post_id = $_GET['post'];
            
            $query_check_likes = "SELECT * FROM Rating WHERE postID='$post_id' AND userID='$id'";
            $results = mysqli_query($db, $query_check_likes);
            $like = mysqli_fetch_assoc($results);
            // need to get current rate
            $query0 = "SELECT * FROM Posts WHERE postID='$post_id'";
            $results = mysqli_query($db, $query0);
            $post = mysqli_fetch_assoc($results);
            $likes = $post['likes'];
            $likes ++;
            
            $query1 = "UPDATE Posts SET likes='$likes' WHERE postID='$post_id'";
            mysqli_query($db, $query1);
            
            $query2 = "INSERT INTO Rating (postID, userID) 
            	   VALUES('$post_id', '$id')";
            mysqli_query($db, $query2);
            
            unset($_POST['like']);
            echo("<meta http-equiv='refresh' content='1'>");
        }
        if (isset($_POST['unlike'])) {
        
            $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            if ($db->connect_error) {
                      die("Connection failed: " . $db->connect_error);
            }
            $username = $_SESSION['username'];
            $query = "SELECT * FROM Users WHERE username ='$username'";
            $results = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            $post_id = $_GET['post'];
            
            // unlike the post
            // need to get current rate
            $query0 = "SELECT * FROM Posts WHERE postID='$post_id'";
            $resuts = mysqli_query($db, $query0);
            $post = mysqli_fetch_assoc($results);
            $likes = $post['likes'];
            $likes --;
                
            $query1 = "UPDATE Posts SET likes='$likes' WHERE postID='$post_id'";
            mysqli_query($db, $query1);
            
            $query2 = "DELETE FROM Rating WHERE postID='$post_id' AND userID='$id'";
            mysqli_query($db, $query2);
                
            unset($_POST['unlike']);
            echo("<meta http-equiv='refresh' content='1'>");
        }
        if (isset($_POST['comment'])) {
          
            $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            if ($db->connect_error) {
                      die("Connection failed: " . $db->connect_error);
            }
            $username = $_SESSION['username'];
            $query = "SELECT * FROM Users WHERE username = '$username'";
            $results = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            $post_id = $_GET['post'];
            
            $comment = $_POST['newcomment'];
            if ($comment != "") {
                $query_addcomment = "INSERT INTO Comments (postID, userID, comment) 
              	VALUES('$post_id', '$id', '$comment')";
                mysqli_query($db, $query_addcomment);
            }

            
            unset($_POST['comment']);
            echo("<meta http-equiv='refresh' content='1'>");
        }
            if (isset($_POST['save'])) {
         
                $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                if ($db->connect_error) {
                          die("Connection failed: " . $db->connect_error);
                }
                $post_id = $_GET['post'];
                $username = $_SESSION['username'];
                $query = "SELECT * FROM Users WHERE username = '$username'";
                $results = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results);
                $id = ($user['userID']);
                
                    $query_savepost = "INSERT INTO savedPosts (userID, postID) 
                  	VALUES('$id', '$post_id')";
                    mysqli_query($db, $query_savepost);
                
                unset($_POST[$tempname]);
                echo("<meta http-equiv='refresh' content='1'>");
            }
            if (isset($_POST['unsave'])) {
             
                $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                if ($db->connect_error) {
                          die("Connection failed: " . $db->connect_error);
                }
                $post_id = $_GET['post'];
                $username = $_SESSION['username'];
                $query = "SELECT * FROM Users WHERE username = '$username'";
                $results = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results);
                $id = ($user['userID']);
                
                    $query_unsavepost = "DELETE FROM savedPosts WHERE userID='$id' AND postID='$post_id'";
                    mysqli_query($db, $query_unsavepost);
                
                unset($_POST[$tempname]);
                echo("<meta http-equiv='refresh' content='1'>");
            }
            
?>
    
<!-- VIEW POST PAGE -->
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
        .btn-primary2:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary
        {
            background-color: #F08080;
            border-color: #F08080;
            color:#fff;
        }
        .btn-primary2
        {
            color:#fff;
            background-color: coral;
            border-color: coral;
        }
    </style>
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html" ?>
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container">
         <br>
         <?php
            ?><br>
            <div class = "row row-cols-3">
            <div class = "col-lg-3 col-md-3 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>View Post</h2>
             </div></div>
            </div>
            <div class = "col-lg-5 col-md-5 ">
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
        <div class="border border-0 border-dark rounded-3 mb-5 bg-transparent">
             
</script> 
    
    <?php
        echo '<div class = "card border-2 border-dark shadow-lg"">';
            echo '<div class = "card body border-0 mt-2">';
                echo '<ul class="list-group list-group-flush">';
                
                
                
        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
                  die("Connection failed: " . $db->connect_error);
        }
        $username = $_SESSION['username'];
        $query = "SELECT * FROM Users WHERE username = '$username' ";
        $results = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($results);
        $id = ($user['userID']);
    
        $post_id = $_GET['post'];
        $post_information_query = "SELECT * FROM Posts WHERE postID='$post_id'";
        $post_information_result = mysqli_query($db, $post_information_query);
        $post_information = mysqli_fetch_assoc($post_information_result);
        
        
        // view usernames account button 
        $post_userID = $post_information['userID'];
        $get_username_query = "SELECT * FROM Users WHERE userID='$post_userID'";
        $post_username_result = mysqli_query($db, $get_username_query);
        $user_information = mysqli_fetch_assoc($post_username_result);
        $post_username = $user_information['username'];
        

            
        
        echo '<li class="list-group-item border-0">';
        // display post
        $image_src = $post_information['imgSrc'];
        echo "<img src=\"$image_src\" style=\"width:100%; border:2px solid black;\" class=\"card-img-top\"><br>";
        echo '</li>';
        echo '<li class="list-group-item border-0">';
        echo '<span class="d-block">';
            
                // display rate stars
                // check if post is already liked by user
                $query_check_likes = "SELECT * FROM Rating WHERE postID='$post_id' AND userID='$id'";
                $resuts = mysqli_query($db, $query_check_likes);
                $resuts2 = mysqli_fetch_assoc($resuts);
                $rate = $post_information['likes'];
                
                $q = "SELECT * FROM savedPosts WHERE userID='$id' AND postID='$post_id'";
                $r = mysqli_query($db, $q);
                $r2 = mysqli_fetch_assoc($r);
                
                // Line of buttons beneath picture, varies based upon what is liked or saved.
                // Buttons are: Like, Save, Comment (with input box) and Report.
                if ($resuts2['userID']) {
                    if ($r2['userID']) {
                        echo "<img src=\"images/like.png\" style=\"width:3%\"> by $rate users.";
                        echo "<form method=\"post\">
                        <input type=\"submit\" class=\"btn btn-primary2 btn-sm\" value=\"Liked\" name=\"unlike$current_index\">
                            <input type=\"submit\" class=\"btn btn-primary2 btn-sm\" value=\"Saved\" name=\"unsave$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" class=\"btn btn-danger btn-sm\" value=\"Report\">
                        </form>";
                        
                    } else {
                        echo "<img src=\"images/like.png\" style=\"width:3%\"> by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" class=\"btn btn-primary2 btn-sm\" value=\"Liked\" name=\"unlike$current_index\">
                            <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Save Post\" name=\"save$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" class=\"btn btn-danger btn-sm\" value=\"Report\">
                        </form>";
                        
                    }
                } else {
                    if ($r2['userID']) {
                        echo "<img src=\"images/like.png\" style=\"width:3%\"> by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Like\" name=\"like$current_index\">
                            <input type=\"submit\" class=\"btn btn-primary2 btn-sm\" value=\"Saved\" name=\"unsave$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" class=\"btn btn-danger btn-sm\" value=\"Report\">
                        </form>";
                        
                    } else {
                        
                        echo "<img src=\"images/like.png\"  style=\"width:3%\"> by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Like\" name=\"like$current_index\">
                            <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Save Post\" name=\"save$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" class=\"btn btn-danger btn-sm\" value=\"Report\">
                        </form>";
                        
                    }
                }
                
            echo "</span>";
            echo '</li>';
        
            
            echo '<ul class="list-group list-group-flush">';
            
            // check if post is part of a challenge
            $challengeid = $post_information['challengeID'];
            if ($challengeid) {
                echo '<li class="list-group-item  border-0">';
                echo "<a style=\"color:black\"><b> This post is an entry in a <a href='viewchallenge.php?challenge=$challengeid' style=\"color:LightSeaGreen\">challenge. </a></b></a>"; 
                echo '</li>';
            }
            
            // check if part of a channel
            $sql = "SELECT * FROM postsInChannel WHERE postID='$post_id'";
            $re = mysqli_query($db, $sql);
            $postinchannel = mysqli_fetch_assoc($re);
            $chanid = ($postinchannel['channelID']);
            if ($chanid) {
                echo '<li class="list-group-item  border-0">';
                echo "<a style=\"color:black\"><b> This post is from a <a href='viewchannel.php?channel=$chanid' style=\"color:LightSeaGreen\">channel.</a></h5></b></a>"; 
                echo '</li>';
            }
        
            // display username with caption
            // we already have username
            echo '<li class="list-group-item border-0">';
                $description = $post_information['description'];
                echo "<a style = \"font-weight: bold; color:LightSeaGreen\" href ='youraccount.php?view_username=$post_username'>@$post_username:</a> <a style = \"font-weight: bold;\">$description</a><br>";
            // tags
            $query_tags = "SELECT * FROM Tags WHERE postID='$post_id'";
            $results = mysqli_query($db, $query_tags);
            while ($tag = mysqli_fetch_array($results)) {
                $temp_tag = $tag['tag'];
                echo "#$temp_tag ";
            }
            echo "<br>";
                $location = $post_information['geotag'];
                echo "<a><img src=\"images/globe.webp\" style=\"width:3%\"><img src=\"images/locationpin.png\" style=\"width:3%\">: $location</a><br>";

                // datetime
                $datetime = $post_information['datetime'];
                echo "<img src=\"images/datetime.png\" style=\"width:3%\">: $datetime";
            echo '</li>';
            
            // display rate stars
            // check if post is already liked by user
            echo '<li class="list-group-item border-0">';
            $query_check_likes = "SELECT * FROM Rating WHERE postID='$post_id' AND userID='$id'";
            $resuts = mysqli_query($db, $query_check_likes);
            $resuts2 = mysqli_fetch_assoc($resuts);
            $rate = $post_information['likes'];

            $q = "SELECT * FROM savedPosts WHERE userID='$id' AND postID='$post_id'";
            $r = mysqli_query($db, $q);
            $r2 = mysqli_fetch_assoc($r);
            
            // display comments
            $comments_count = 0;
            $query_getcomments = "SELECT * FROM Comments WHERE postID='$post_id'";
            $resuts_comments = mysqli_query($db, $query_getcomments);
            while ($row = mysqli_fetch_array($resuts_comments)) {
                $comment_userid = $row['userID'];
                $comment = $row['comment'];
                // get the username from userid
                $query_name = "SELECT username FROM Users WHERE userID='$comment_userid'";
                $resuts2 = mysqli_query($db, $query_name);
                $user = mysqli_fetch_assoc($resuts2);
                $print_username = $user['username'];
                echo "<img src=\"images/comments.webp\" style=\"width:3%\"><a href ='youraccount.php?view_username=$print_username' style=\"color:LightSeaGreen\">@$print_username:</a> <a >$comment</a><br>";
                $comments_count++;
            }
            if ($comments_count == 0) {
                echo "There are no <img src=\"images/comments.webp\" style=\"width:3%\"> on this post.<br>";
            }
            
                        echo "<form method=\"post\" class=\"d-flex\">
                <input type=\"text\" class=\"form-control\" id=\"newcomment\" name=\"newcomment\" placeholder=\"comment feedback for this picture...\">&nbsp
                <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Add Comment\" name=\"comment$current_index\"></form>";
                
        
        echo "</li></div></div>";
        
        
    

    
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