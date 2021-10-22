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
  ini_set('session.cache_limiter','public');
  session_cache_limiter(false);
  
?>
<!-- MY ACCOUNT PAGE -->
<!DOCTYPE html>
<html>
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
    <?php include "includes/myaccountheader.html" ?>
    <?php include "includes/carousel.html" ?>
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; 
    $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        $username = $_SESSION['username'];
    ?>
     <div class="container">
         <br>
         <div class = "row row-cols-3">
            <div class = "col-xl-4 col-lg-5 col-md-6 col-8 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <?php
         echo "<h2>@$username</h2></li>"; // username
         ?>
             </div></div>
            </div>
            <div class = "col-xl-4 col-lg-3 col-md-2">
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
    <div class="border border-0 rounded-3 mb-5 bg-transparent">

        <?php 
    
        
        echo '<div class = "card border-dark border-2 shadow-lg ">';
        echo '<div class = "card body border-0">';
        echo '<ul class="list-group list-group-flush">';
        
        ?>
        <li class="list-group-item border-0">
        <button id="settings" class="btn btn-primary btn-sm"  onclick="location.href='savedposts.php'">Saved Posts</button>
        <button id="settings" class="btn btn-primary btn-sm"  onclick="location.href='accountsettings.php'">Account Settings</button>
        </li>
        
        <?php
        
        echo '<li class="list-group-item border-0">';
          // display total likes
          $query_totallikes = "SELECT * FROM Users WHERE username='$username'";
          $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
          $result_users = mysqli_query($conn, $query_totallikes);
          $user = mysqli_fetch_assoc($result_users);
          $username_id = $user['userID'];
          $query_postsbyuser = "SELECT * FROM Posts WHERE userID='$username_id'";
          $result_posts = mysqli_query($conn, $query_postsbyuser);
          $total_likes = 0;
          $post_count = 0;
          while ($row = mysqli_fetch_array($result_posts)) {
              $postlikes = $row['likes'];
              $total_likes = $total_likes + $postlikes;
              $post_count++;
          }
          echo "<h5><img src=\"images/like.png\" style=\"width:3%\">Total Likes: $total_likes </h5>";
          $average_likes = $total_likes / $post_count;
          // echo "<h5><img src=\"images/like.png\" style=\"width:3%\">Average Likess per post: $average_likes </h5>";
              
          // display followers
          $query_totalfollowers = "SELECT * FROM Followers WHERE userID='$username_id'";
          $followers = mysqli_query($conn, $query_totalfollowers);
          $followers_count = 0;
          while ($row = mysqli_fetch_array($followers)) {
            $followers_count++;
          }
          echo "<h5><img src=\"images/followers.png\" style=\"width:3%\"> Followers: $followers_count </h5>";
          
          // display challenges won
          $challenge_won_count = 0;
          $query_totalwins = "SELECT * FROM challenge_winners WHERE userID='$username_id'";
          $wins = mysqli_query($conn, $query_totalwins);
          while ($row = mysqli_fetch_array($wins)) {
              $challenge_won_count++;
          }
          echo "<h5><img src=\"images/won.webp\" style=\"width:3%\"> Challenges Won: $challenge_won_count </h5>";
        echo ' </li></ul>'; 
        echo '</div></div><br><br>';
        
        // now loop through all of my posts and post them with delete button
        $username = $_SESSION['username'];
        
        $query_totallikes = "SELECT * FROM Users WHERE username='$username'";
          $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
          $result_users = mysqli_query($conn, $query_totallikes);
          $user = mysqli_fetch_assoc($result_users);
          $username_id = $user['userID'];
          $query_postsbyuser = "SELECT * FROM Posts WHERE userID='$username_id' ORDER BY datetime DESC";
          $result_posts = mysqli_query($conn, $query_postsbyuser);
          // add post ids to array for deleting so then we can just index
          $post_ids = array();
          $posts_count = 0;
          while ($row = mysqli_fetch_array($result_posts)) {
            echo '<div class = "card border-dark border-2 shadow-lg">';
            echo '<div class = "card body border-0 mt-2">';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item border-0">';
              $src = $row['imgSrc'];
              $thispostid = $row['postID'];
              $post_ids[$posts_count] = $thispostid;
              
              // echo image
              echo "<img src=\"$src\" style=\"width:100%; border:2px solid black;\" class=\"card-img-top\"><br>";
              echo '</li>';
              // display description
              echo '<li class="list-group-item border-0">';
              $caption = $row['description'];
              echo "Your <img src=\"images/comments.webp\" style=\"width:3%\"> of this post: $caption<br>";
              
              // display comments
              
              // display total likes
              $likes = $row['likes'];
              echo "Total <img src=\"images/like.png\" style=\"width:3%\">s: $likes <br>";
              echo '</li>';
              echo '<li class="list-group-item border-0">';
              echo "<form method=\"post\" action='viewpost.php?post=$thispostid'>
                        <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View Post\" name=\"viewPost\">
                    </form>";
              echo '</li>';
              echo '<li class="list-group-item border-0">';
              // echo delete button
              echo "Do you want to delete this post?";
              echo "<form method=\"post\"><input type=\"submit\" class=\"btn btn-danger btn-sm\" value=\"Delete\" name=\"delete$posts_count\" onclick=\"return confirm('Are you sure you want to delete this post?')\"></form>";
              $posts_count++;
              echo '</li></ul></div></div><br><br>';
          }
          
          if ($posts_count == 0) {
                echo '<div class = "card border-dark border-2 shadow-lg ">';
                echo '<div class = "card body border-0 mt-1">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item  border-0 mb-1">';
                echo "You have no posts yet.<br>";
                echo "Click the create new post button to create a new post.";
                echo '</li></ul></div></div>';
                
                
          }
          $index = 0;
          for ($index; $index < $posts_count; $index++) {
              $temp = "delete$index";
              if(isset($_POST[$temp])) {
                  $delete_postid = $post_ids[$index];
                  
                  
                  // remove post from posts table
                  $query1 = "DELETE FROM Posts WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query1);
                  
                  // remove comments
                  $query2 = "DELETE FROM Comments WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query2);
                  
                  // remove rates
                  $query3 = "DELETE FROM Rating WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query3);
                  
                  // remove tags
                  $query4 = "DELETE FROM Tags WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query4);

                  // remove from channel
                  $query5 = "DELETE FROM postsInChannel WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query5);
                  
                  // refresh page
                  echo("<meta http-equiv='refresh' content='1'>");
              }
          }
          echo "<br><br>";
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
<?php include "includes/footer.php" ?>
</body>
</html>
