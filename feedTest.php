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



<!-- required html -->
<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body style = "background-color:transparent">
    <?php include "includes/header.html" ?> <!-- Calls the header file -->
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
    <?php include "includes/carousel.html" ?>
    <div class="container" style = "background-color:transparent">
       <br><br>
       <div class = "row row-cols-3">
            <div class = "col-sm-2 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Your Feed</h2>
             </div></div>
            </div>
            <div class = "col-sm-6">
            </div>
            <div class = " d-none d-sm-block col-sm-3">
                <div class = "card border-2 border-dark shadow-lg">
                <div class = "card body border-0 m-2" align="center">
                <h2>Notifications</h2>
                </div>
                </div>
            </div>
            
             
        </div><br>
    
    <div class="row row-cols-2">
    
    <!-- Main content of current page -->
    <div class="col-sm-8 col-12 d-block">
    <div class="border border-2 border-0 rounded-3 mb-5 bg-transparent">
    <?php
        // -------SETTING UP FEED AND FINDING POSTS---------------------------------
        
        
        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
                  die("Connection failed: " . $db->connect_error);
        }
        
        $username = $_SESSION['username'];
        $post_ids = array();
        $post_ids_index = 0;
        
        $query = "SELECT * FROM Users WHERE username = '$username' ";
        $results = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($results);
        $id = ($user['userID']);
        
        $query2 = "SELECT *
                        FROM Followers
                        JOIN Posts
                        ON Followers.userID = Posts.userID
                        WHERE followedBy = '$id'
                        ORDER BY datetime DESC";
                        
        $query3 = "SELECT Posts.postID, Posts.datetime
                        FROM Followers
                        JOIN Posts
                        ON Followers.userID = Posts.userID
                        WHERE followedBy = '$id' 
                        UNION
                        SELECT Posts.postID, Posts.datetime
                        FROM usersInChannel
                        JOIN postsInChannel 
                        ON postsInChannel.channelID = usersInChannel.channelID
                        JOIN Posts
                        ON Posts.postID = postsInChannel.postID
                        WHERE usersInChannel.userID = '$id'
                        
                        ORDER BY datetime DESC";
                        
        $results = mysqli_query($db, $query3);
        while ($row = mysqli_fetch_array($results)) {
            // get the temp postID from the db
            $temp_postid = $row['postID'];
            // add the temp post id to the index in the array
            $post_ids[$post_ids_index] = $temp_postid;
            // increment the index
            $post_ids_index++;
        }
        
        // now display the first post in the array
        $posts_count = $post_ids_index;
        $current_index = 0;
        
        if ($posts_count == 0) {
            echo "There are no posts found for your feed.<br>";
            echo "Search for users to follow them to see their posts in your feed.<br><br>";
        }
        
    
        // call each post to populate the feed and places it within its own card
        while ($current_index < $posts_count) {
            echo '<div class = "card border-2 border-dark shadow-lg">';
            echo '<div class = "card body border-0  mt-2">';
            
            $post_id = $post_ids[$current_index];
            $post_information_query = "SELECT * FROM Posts WHERE postID='$post_id'";
            $post_information_result = mysqli_query($db, $post_information_query);
            $post_information = mysqli_fetch_assoc($post_information_result);
            echo '<ul class="list-group list-group-flush">';
            
            
            // view usernames account button 
            $post_userID = $post_information['userID'];
            $get_username_query = "SELECT * FROM Users WHERE userID='$post_userID'";
            $post_username_result = mysqli_query($db, $get_username_query);
            $user_information = mysqli_fetch_assoc($post_username_result);
            $post_username = $user_information['username'];
            
            echo '<li class="list-group-item  border-0">';
            
            // display post
            $image_src = $post_information['imgSrc'];
            echo "<img src=\"$image_src\" style=\"width:100%\" class=\"card-img-top\"><br>";
            echo '</li>';
            echo '<li class="list-group-item  border-0">';
            
            
            echo '<span class="d-block ">';
            
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
                        echo "Liked by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" style=\"background-color:coral\" value=\"Liked\" name=\"unlike$current_index\">
                            <input type=\"submit\" style=\"background-color:coral\" value=\"Saved\" name=\"unsave$current_index\">
                            <input type=\"text\" id=\"newcomment\" name=\"newcomment\"> 
                            <input type=\"submit\" value=\"Add Comment\" name=\"comment$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" style=\"background-color: coral\" value=\"Report\">
                        </form>";
                        
                    } else {
                        echo "Liked by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" style=\"background-color:coral\" value=\"Liked\" name=\"unlike$current_index\">
                            <input type=\"submit\" value=\"Save Post\" name=\"save$current_index\">
                            <input type=\"text\" id=\"newcomment\" name=\"newcomment\">
                            <input type=\"submit\" value=\"Add Comment\" name=\"comment$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" style=\"background-color: coral\" value=\"Report\">
                        </form>";
                        
                    }
                } else {
                    if ($r2['userID']) {
                        echo "Liked by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" value=\"Like\" name=\"like$current_index\">
                            <input type=\"submit\" style=\"background-color:coral\" value=\"Saved\" name=\"unsave$current_index\">
                            <input type=\"text\" id=\"newcomment\" name=\"newcomment\">
                            <input type=\"submit\" value=\"Add Comment\" name=\"comment$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" style=\"background-color: coral\" value=\"Report\">
                        </form>";
                        
                    } else {
                        
                        echo "Liked by $rate users.";
                        echo "<form method=\"post\"><input type=\"submit\" value=\"Like\" name=\"like$current_index\">
                            <input type=\"submit\" value=\"Save Post\" name=\"save$current_index\">
                            <input type=\"text\" id=\"newcomment\" name=\"newcomment\">
                            <input type=\"submit\" value=\"Add Comment\" name=\"comment$current_index\">
                            <input type=\"button\" onclick=\"location.href='report.php?post=$post_id';\" style=\"background-color: coral\" value=\"Report\">
                        </form>";
                        
                    }
                    
                }
                
                
            echo "</span>";
            // save post
            
            
            // check if post is part of a challenge
            $challengeid = $post_information['challengeID'];
            if ($challengeid) {
                echo '<li class="list-group-item  border-0">';
                echo "<p style=\"color:coral\"><b> This post is an entry in a challenge. <input type=\"button\" onclick=\"location.href='viewchallenge.php?challenge=$challengeid';\" value=\"View Challenge\" /></b></p>"; 
                echo '</li>';
            }
            
            // check if part of a channel
            $sql = "SELECT * FROM postsInChannel WHERE postID='$post_id'";
            $re = mysqli_query($db, $sql);
            $postinchannel = mysqli_fetch_assoc($re);
            $chanid = ($postinchannel['channelID']);
            if ($chanid) {
                echo '<li class="list-group-item  border-0">';
                echo "<p style=\"color:coral\"><b> This post is from a channel.</h5><input type=\"button\" onclick=\"location.href='viewchannel.php?channel=$chanid';\" value=\"View Channel\" /></b></p>"; 
                echo '</li>';
            }
            
            
            echo '<li class="list-group-item  border-0">';
            // display username with caption
            // we already have username
            $description = $post_information['description'];
            echo "<a href ='youraccount.php?view_username=$post_username'>@$post_username:</a> $description<br>";
            echo '</li>';
            
            echo '<li class="list-group-item  border-0">';
            // display comments
            echo "<Comments:<br>";
            $comments_count = 0;
            $query_getcomments = "SELECT * FROM Comments WHERE postID='$post_id' LIMIT 5";
            $resuts_comments = mysqli_query($db, $query_getcomments);
            while ($row = mysqli_fetch_array($resuts_comments)) {
                $comment_userid = $row['userID'];
                $comment = $row['comment'];
                // get the username from userid
                $query_name = "SELECT username FROM Users WHERE userID='$comment_userid'";
                $resuts2 = mysqli_query($db, $query_name);
                $user = mysqli_fetch_assoc($resuts2);
                $print_username = $user['username'];
                echo "@$print_username: $comment<br>";
                $comments_count++;
            }
            if ($comments_count == 0) {
                echo "There are no comments on this post.<br>";
            }
            // button to view all comments
            echo "<form method=\"post\" action='viewpost.php?post=$post_id'>
                        <input type=\"submit\" value=\"View all comments\" name=\"viewPost\">
                    </form>";
            
            
            
            echo '</li></ul>';
        
            // post is complete so increment
            $current_index++;
            echo '</div></div>';
            echo '<br><br>';
        }
        
        $index = 0;
        for ($index; $index < $posts_count; $index++) {
            
            // check if post was liked
            $tempname = "like$index";
            if (isset($_POST[$tempname])) {
                $post_id = $post_ids[$index];
                $username = $_SESSION['username'];
                $query = "SELECT * FROM Users WHERE username = '$username'";
                $results = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results);
                $id = ($user['userID']);
                
                //$query_check_likes = "SELECT * FROM Rating WHERE postID='$post_id' AND userID='$id'";
                //$resuts = mysqli_query($db, $query_check_likes);
                //$like = mysqli_fetch_assoc($results);
                
                // need to get current rate
                $query0 = "SELECT * FROM Posts WHERE postID='$post_id'";
                $results = mysqli_query($db, $query0);
                $post = mysqli_fetch_assoc($results);
                $likes = $post['likes'];
                $likes ++;
                
                $query1 = "UPDATE Posts SET likes=$likes WHERE postID='$post_id'";
                mysqli_query($db, $query1);
                
                $query2 = "INSERT INTO Rating (postID, userID, seen) 
                	   VALUES('$post_id', '$id', 0)";
                mysqli_query($db, $query2);
                
                unset($_POST[$tempname]);
                echo("<meta http-equiv='refresh' content='1'>");
            }
            $tempname = "unlike$index";
            if (isset($_POST[$tempname])) {
                $post_id = $post_ids[$index];
                $username = $_SESSION['username'];
                $query = "SELECT * FROM Users WHERE username ='$username'";
                $results = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results);
                $id = ($user['userID']);
                echo "postid: $post_id<br><br><br>";
                // unlike the post
                // need to get current rate
                $query0 = "SELECT * FROM Posts WHERE postID='$post_id'";
                $results = mysqli_query($db, $query0);
                $post = mysqli_fetch_assoc($results);
                $likes = $post['likes'];
                $likes--;
                echo "likes: $likes<br><br><br>";
                    
                $query1 = "UPDATE Posts SET likes=$likes WHERE postID='$post_id'";
                mysqli_query($db, $query1);
                
                $query2 = "DELETE FROM Rating WHERE postID='$post_id' AND userID='$id'";
                mysqli_query($db, $query2);
                    
                unset($_POST[$tempname]);
                echo("<meta http-equiv='refresh' content='1'>");
            }
            $tempname = "comment$index";
            if (isset($_POST[$tempname])) {
                $post_id = $post_ids[$index];
                $username = $_SESSION['username'];
                $query = "SELECT * FROM Users WHERE username = '$username'";
                $results = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results);
                $id = ($user['userID']);
                
                $comment = $_POST['newcomment'];
                if ($comment != "") {
                    $query_addcomment = "INSERT INTO Comments (postID, userID, comment, seen) 
                  	VALUES('$post_id', '$id', '$comment', '0')";
                    mysqli_query($db, $query_addcomment);
                }
    
                unset($_POST[$tempname]);
                echo("<meta http-equiv='refresh' content='1'>");
            }
            $tempname = "save$index";
            if (isset($_POST[$tempname])) {
                $post_id = $post_ids[$index];
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
            $tempname = "unsave$index";
            if (isset($_POST[$tempname])) {
                $post_id = $post_ids[$index];
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
<br>
<?php include "includes/footer.php" ?> <!-- Calls the footer -->
</body>
</html>
