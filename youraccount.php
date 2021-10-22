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
  
  $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        $username = $_GET['view_username'];
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
</head>
<body>
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html" ?>
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container">
         <br><br>
         <div class = "row row-cols-3">
            <div class = "col-xl-4 col-lg-5 col-md-6 col-8 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
              <?php 
               echo "<h2>@$username</h2>";
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
        
    <div class="border border-0 rounded-3 mb-5 bg-transparent">

        <?php 
        
        echo '<div class = "card border-dark border-2">';
        echo '<div class = "card body border-0">';
        echo '<ul class="list-group list-group-flush">';
        echo '<li class="list-group-item border-0">';
        
        
        
        $currentuserusername = $_SESSION['username'];
          $query_totallikes = "SELECT * FROM Users WHERE username='$currentuserusername'";
          $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
          $result_users = mysqli_query($conn, $query_totallikes);
          $user = mysqli_fetch_assoc($result_users);
          $currentuserid = $user['userID'];
        
          $query_totallikes = "SELECT * FROM Users WHERE username='$username'";
          $result_users = mysqli_query($conn, $query_totallikes);
          $user = mysqli_fetch_assoc($result_users);
          $username_id = $user['userID'];
          $privacy = $user['public'];
            $q2 = "SELECT * FROM Followers WHERE userID='$username_id' AND followedBy='$currentuserid'";
            $res = mysqli_query($db, $q2);
            $followedby = mysqli_fetch_assoc($res); 
            if ($followedby || ($privacy==1)) {
            
            
                    if ($followedby) {
                        echo "
                            <form method=\"post\">
                                <input type=\"submit\" class=\"btn btn-primary2 btn-sm\" value=\"Unfollow\" name=\"unfollow\">
                            </form>
                        ";
                    } else {
                        echo "
                            <form method=\"post\">
                                <input type=\"submit\" value=\"Follow\" name=\"follow\" class=\"btn btn-primary btn-sm\">
                            </form>
                        ";
                    }
        
          if (isset($_POST['unfollow'])) {
                    $queryunfollow = "Delete FROM Followers where userID='$username_id' AND followedBy='$currentuserid'";
                    mysqli_query($conn, $queryunfollow);
              
              
          	unset($_POST['unfollow']);
          	echo("<meta http-equiv='refresh' content='1'>");
          }
          
          if (isset($_POST['follow'])) {
                    $queryfollow = "INSERT INTO Followers (userID, followedBy) VALUES ('$username_id','$currentuserid')";
                    mysqli_query($conn, $queryfollow);
              
              
          	unset($_POST['follow']);
          	echo("<meta http-equiv='refresh' content='1'>");
          }
        
        echo "</li>";
        
        echo '<li class="list-group-item border-0">';

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
          //echo "<h5>Average likes per post: $average_likes </h5>";
              
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
        echo ' </li></ul></div></div><br><br>';
        // now loop through all of my posts and post them with delete button
        
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
            echo '<div class = "card border-dark border-2">';
            echo '<div class = "card body border-0 mt-2">';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item border-0">';
              $src = $row['imgSrc'];
              $thispostid = $row['postID'];
              $post_ids[$posts_count] = $thispostid;
              
              // echo image
              echo "<img src=\"$src\" style=\"width:100%; border:2px solid black;\" class=\"card-img-top\"><br>";
              
              
              echo '<li class="list-group-item border-0">';
              echo "<center>";
              echo "<form method=\"post\" action='viewpost.php?post=$thispostid'>
                        <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View Post\" name=\"viewPost\">
                    </form>";
            echo "</center>";
              echo '</li>';
              $posts_count++;
              echo '</ul></div></div><br><br>';
          }
          
          if ($posts_count == 0) {
                echo '<div class = "card border-dark border-2 shadow-lg ">';
                echo '<div class = "card body border-0 mt-1">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item  border-0 mb-1">';
                echo "This user has no posts.<br>";
                echo '</li></ul></div></div>';
                
                
          }
          
          $index = 0;
          
            } else {
                echo '<li class="list-group-item  border-0 mb-1">';
                echo "This account is private. To view their posts they must accept your request to follow them.<br>";
                        echo "
                            <form method=\"post\">
                                <input type=\"submit\" value=\"Request to Follow\" name=\"requestfollow\">
                            </form>
                        ";
                        
                        if (isset($_POST['requestfollow'])) {
                            
                            // check if there is already a request
                            $q = "SELECT * FROM request_follow WHERE privateuserID='$username_id' AND wanttofollowuserID='$currentuserid'";
                            $r = mysqli_query($conn, $q);
                            $requestexists = mysqli_fetch_assoc($r);
                            
                            if (!$requestexists) {
                                $queryfollowreq = "INSERT INTO request_follow (privateuserID, wanttofollowuserID) VALUES ('$username_id','$currentuserid')";
                                mysqli_query($conn, $queryfollowreq);
                                echo "Follow request sent.<br>";
                            } else {
                                echo "You have already requested to follow this user.<br>";
                            }
                            unset($_POST['requestfollow']);
                        }
                        echo ' <br></li></ul></div></div><br><br>';
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
<?php include "includes/footer.php" ?>
</body>
</html>