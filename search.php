<?php
    session_start();
  
    
    if (!isset($_SESSION['username'])) {
        session_start(); 
  	    $_SESSION['msg'] = "You must log in first";
  	    header('location: login.php');
    }
    
    if (isset($_GET['logout'])) {
  	    session_destroy();
  	    unset($_SESSION['username']);
  	    header('location: login.php');
    } 
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
<body id="body" onload="search()">
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

       <div class="container">
           <br><br>
           <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-5 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Search Results</h2>
             </div></div>
            </div>
            <div class = "col-md-5">
            </div>
            <div class = " d-none d-md-block col-md-3">
                
                
                
                
            </div>
        </div><br>
    <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
 
 <?php
    // -------Search Results----------------------------------------------------
        // save the value
        session_start(); 
        $search = $_GET['search_value'];
        echo "<h2>Showing results for $search:</h2><br>";
        
        // chack the value not empty (the user entered something)
        if ($search != "") {
            // connect to db
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            // make sure connection is good
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // now we need to find the results but we have to check 2 things: 1. usernames and 2. tags
                
                // -----find users-----
                echo "<h5>Users found with this username: </h5>";
                // we know there will only be at most 1 result because we have a constraint that username must be unique.
                $query_users = "SELECT * From Users WHERE username LIKE '%$search%'";
                $result_users = mysqli_query($conn, $query_users);
                
                while( $row = mysqli_fetch_assoc($result_users)){
                     $array[] = $row['username'];
                    
                    }
  
                // if user exists
                if (!is_null($array)) {
                    foreach($array as $value){
                    echo "<h2>@$value</h2>";
                    echo "<form method=\"post\" action='youraccount.php?view_username=$value'>
                                <input type=\"submit\" value=\"View user account\" name=\"viewaccount\" class=\"btn btn-primary btn-sm\">
                            </form>";
                    echo "<br>";
                    }
                } else {
                    echo "No users found with username like: @$search <br>";
                }
                
                // -----find tags-----
                echo "<br><h5>Posts found with this tag: </h5>";
                $query_tags = "SELECT * From Tags WHERE tag LIKE '%$search%'";
                $result_tags = mysqli_query($conn, $query_tags);
                $results_found = 0;
                
                while ($row = mysqli_fetch_array($result_tags)) {
                    $results_found++;
                    $postid = $row['postID'];
                    $tag = $row['tag'];
                    // now we need to find the image src for the post with this postid
                    $query_post = "SELECT * From Posts WHERE postID='$postid'";
                    $result_post = mysqli_query($conn, $query_post);
                    $post = mysqli_fetch_assoc($result_post);
                    if ($post) {
                        // display image
                        $image_src = $post['imgSrc'];
                        $temp_user_id = $post['userID'];
                        echo "<img src=\"$image_src\" style=\"width:100%\">";
                        echo "<br>";
                        // display username
                        $query_user_id = "SELECT * From Users WHERE userID='$temp_user_id'";
                        $result_user_id = mysqli_query($conn, $query_user_id);
                        $user = mysqli_fetch_assoc($result_user_id);
                        if ($user) {
                            $username = $user['username'];
                            // display button with rate options
                            echo "Similar tag on this post: #$tag<br>";
                            echo "
                                <form method=\"post\" action='viewpost.php?post=$postid'>
                                    <input type=\"submit\" value=\"View post\" name=\"viewpost\" class=\"btn btn-primary btn-sm\">
                                </form>
                            ";
                            echo "<br>";
                        } 
                    }
                }
                if ($results_found == 0) {
                    echo "No Posts found with tag like: #$search<br><br>";
                }
                
                // -----find geotags-----
                echo "<br><h5>Posts found with this geotag: </h5>";
                $query_tags = "SELECT * From Posts WHERE geotag LIKE '%$search%'";
                $result_tags = mysqli_query($conn, $query_tags);
                $results_found = 0;
                
                while ($row = mysqli_fetch_array($result_tags)) {
                    $results_found++;
                    $postid = $row['postID'];
                    $tag = $row['geotag'];
                    // now we need to find the image src for the post with this postid
                    $query_post = "SELECT * From Posts WHERE postID='$postid'";
                    $result_post = mysqli_query($conn, $query_post);
                    $post = mysqli_fetch_assoc($result_post);
                    if ($post) {
                        // display image
                        $image_src = $post['imgSrc'];
                        $temp_user_id = $post['userID'];
                        echo "<img src=\"$image_src\" style=\"width:100%\">";
                        echo "<br>";
                        // display username
                        $query_user_id = "SELECT * From Users WHERE userID='$temp_user_id'";
                        $result_user_id = mysqli_query($conn, $query_user_id);
                        $user = mysqli_fetch_assoc($result_user_id);
                        if ($user) {
                            $username = $user['username'];
                            echo "Similar geotag on this post: #$tag<br>";
                            // display button with rate options
                            echo "
                                <form method=\"post\" action='viewpost.php?post=$postid'>
                                    <input type=\"submit\" value=\"View post\" name=\"viewpost\" class=\"btn btn-primary btn-sm\">
                                </form>
                            ";
                            echo "<br>";
                        } 
                    }
                }
                if ($results_found == 0) {
                    echo "No Posts found with getoag like: #$search<br><br>";
                }
                
                // -----find challenges-----
                echo "<br><h5>Challenges found with this name: </h5>";
                $query_name = "SELECT * From Challenge WHERE challengeName LIKE '%$search%'";
                $result_tags = mysqli_query($conn, $query_name);
                $results_found = 0;
                
                while ($row = mysqli_fetch_array($result_tags)) {
                    $results_found++;
                    $name = $row['challengeName'];
                    $sdate = $row['startDate'];
                    $edate = $row['endDate'];
                    $description = $row['description'];
                    $id = $row['challengeID'];
                            echo "Challenge Name: $name<br>";
                            echo "Start Date: $sdate<br>";
                            echo "End Date: $edate <br>";
                            echo "Challenge Description: $description<br>";
                            echo "
                                <form method=\"post\" action='viewchallenge.php?challenge=$id'>
                                    <input type=\"submit\" value=\"View challenge\" name=\"viewchallenge\" class=\"btn btn-primary btn-sm\">
                                </form><br>
                            ";
                }
                if ($results_found == 0) {
                    echo "No Challenges found with name like: $search<br><br>";
                }
                
                // ---------- TO DO ----------
                echo "<br><h5>Channels found with this name: </h5>";
                $query_name = "SELECT * From Channel WHERE channelName LIKE '%$search%'";
                $result_tags = mysqli_query($conn, $query_name);
                $results_found = 0;
                
                while ($row = mysqli_fetch_array($result_tags)) {
                    $results_found++;
                    $name = $row['channelName'];
                    $description = $row['description'];
                    $userid = $row['userID'];
                    $id = $row['channelID'];
                     
                    $query = "SELECT * FROM Users WHERE userID = '$userid' ";
                    $results = mysqli_query($conn, $query);
                    $user = mysqli_fetch_assoc($results);
                    $username = ($user['username']);
                            echo "Channel Name: $name<br>";
                    echo "Created By: @$username  ";
                    // viewuseraccount button
                    echo "<input type=\"button\" onclick=\"location.href='youraccount.php?view_username=$username';\" value=\"View User Account\" class=\"btn btn-primary btn-sm\" /><br>";
                            echo "Channel Description: $description<br>";
                            echo "
                                <form method=\"post\" action='viewchannel.php?channel=$id'>
                                    <input type=\"submit\" value=\"View channel\" name=\"viewchannel\" class=\"btn btn-primary btn-sm\">
                                </form><br>
                            ";
                }
                if ($results_found == 0) {
                    echo "No Channels found with name like: $search<br><br>";
                }
                
                echo "<br><br><br>";
                
                
            }
        } else {
            echo "No input found.<br>";
            echo "Need to enter a username or tag to search.";
        }
   
  
?>
  </div>
</div>
<br>
<br>
<br>
    <?php include "includes/footer.php" ?>
</body>
</html>
    