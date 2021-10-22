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
<!-- CHANNELS PAGE -->
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
            <div class = "col-lg-3 col-md-4 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Saved Posts</h2>
             </div></div>
            </div>
            <div class = "col-lg-5 col-md-4">
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
            $username = $_SESSION['username'];
            $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            if ($db->connect_error) {
                      die("Connection failed: " . $db->connect_error);
            }
            $query = "SELECT * FROM Users WHERE username = '$username' ";
            $results = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            
            $q = "SELECT * FROM savedPosts WHERE userID='$id'";
            $r = mysqli_query($db, $q);
            $post_ids = array();
            $posts_count = 0;
            while ($p = mysqli_fetch_array($r)) {
                echo '<div class = "card border-dark border-2 shadow-lg">';
                echo '<div class = "card body border-0 mt-2">';
                echo '<li class="list-group-item border-0">';
                $post_id = $p['postID'];
                $post_ids[$posts_count] = $post_id;
                $q2 = "SELECT * FROM Posts WHERE postID='$post_id'";
                $r2 = mysqli_query($db, $q2);
                $post = mysqli_fetch_assoc($r2);
                $src = $post['imgSrc'];
                echo "<img src=\"$src\" style=\"width:100%\" class=\"card-img-top\"><br>";
                echo '</li>';
                echo '<li class="list-group-item border-0">';
                echo "<form method=\"post\"><input type=\"submit\" class=\"btn btn-danger btn-sm\" value=\"Unsave Post\" name=\"unsave$posts_count\"></form>";
                echo '</li>';
                echo '<li class="list-group-item border-0">';
                echo "<form method=\"post\" action='viewpost.php?post=$post_id'>
                            <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View Post\" name=\"viewPost\">
                        </form>";
                
                $posts_count++;
                echo '</li>';
                echo '</div></div><br><br>';
            }
            if ($posts_count == 0) {
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0">';
                echo "You have not saved any posts yet.<br></li></ul></div></div>";
            }
          $index = 0;
          for ($index; $index < $posts_count; $index++) {
              $temp = "unsave$index";
              if(isset($_POST[$temp])) {
                  $unsave_postid = $post_ids[$index];
                  // remove post from posts table
                  $query1 = "DELETE FROM savedPosts WHERE postID='$unsave_postid' AND userID='$id'";
                  mysqli_query($db, $query1);
                  
                  unset($_POST[$temp]);
                  // refresh page
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