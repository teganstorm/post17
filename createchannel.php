<?php 
  session_start(); 
  
  $username = $_GET['username'];
  $_SESSION['username'] = $username;

/*
  if (!isset($_SESSION['username'])) {
      echo " oh no! session storage isnt working on this page";
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  */
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
  $ypos = $_COOKIE["ypos"];
?>
<?php
  $errors = array(); 
    if (isset($_POST['createchannel'])) {

        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $name = ($_POST['name']);
        $description = ($_POST['description']);
        $privacy = ($_POST['privacy']);
        // $username = $_SESSION['username'];
            if (empty($username)) {
              	    echo "<br><br><br><br><br>help";
                }
        //limit channel name size
        if(strlen($name) > 50){
            array_push($errors, "Channel name is too long please keep it below 50 characters!");
        }
        if(strlen($description) > 255){
            array_push($errors, "Channel description is too long please keep it below 255 characters!");
        }
        $channel_data = "SELECT * FROM Channel" ;
        $results = mysqli_query($db, $channel_data);
        $channel_ids = array();
        $channel_index = 0;
      
            while ($row = mysqli_fetch_array($results)) {
                $temp_CID = $row['channelID'];
                $channel_ids[$channel_index] = $temp_CID;
                $channel_index++;
            }
        $num_desc = $channel_index;
        $index =0;
        while ($index < $num_desc) {
                $tmp_id = $channel_ids[$index];
                $tmp_channel_info_query = "SELECT * FROM Channel WHERE channelID = '$tmp_id'";
                $results2 = mysqli_query($db, $tmp_channel_info_query);
                $tmp_channel_info = mysqli_fetch_assoc($results2);
                $tmp_channelName = ($tmp_channel_info['channelName']);
                if ($tmp_channelName == $name) {
                    array_push($errors, "Cannot include a channel with a name that already exists!");
                    break;
                }
         $index++;   
        }
        
         $query = "SELECT * FROM Users WHERE username = '$username' ";
            $results = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($results);
            $id = ($user['userID']);
            
        $public;
        if ($privacy == "public") {
            $public = 1;
        } else {
            $public = 0;
        }
        
      if (count($errors) == 0) {
        $query2 = "INSERT INTO Channel (public, userID, description, channelName) VALUES ('$public', '$id', '$description', '$name')";
        
         if ($db->query($query2) === TRUE) {
              $succesfullMessage = "Successfully created channel!";
            $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
             
             
             
             // now add the user to usersInChannel
             $q = "SELECT * FROM Channel WHERE channelName='$name' AND userID='$id'";
             $results = mysqli_query($db, $q);
             $channel = mysqli_fetch_assoc($results);
             $channelid = $channel['channelID'];
             $query5 = "INSERT INTO usersInChannel (channelID, userID) VALUES ('$channelid', '$id')";
             mysqli_query($db, $query5);
             
             
       
            } else {
                echo "it didn't work" . $query2 . "<br>" . $conn->error;
                //echo("<meta http-equiv='refresh' content='1'>");
            }
        } else {

            header(createchannel.php);
        }
        
           
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
        <body id="body">
                <?php include "includes/header.html" ?>
                <?php include "includes/carousel.html" ?>
                <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
                
    <div class="container">
        <br>
        <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-5 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Create Channel</h2>
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
        <!-- Create new Post -->
          
        
        
        <form id="form" method="post">
                             <?php        echo $messageecho; ?>
                <label id="nametext">Channel Name:</label>
                <input type="text" id="name" name="name" required><br>
    
                <label id="descriptiontext">Channel Description:</label>
                <input type="text" id="description" name="description"><br>
    
    
                <label id="privacytext">Private or Public:</label><br>
                <label for="private" id="privatetext"> - Private</label>
                <input type="radio" id="private" name="privacy" value="private" required><br>
                <label for="public" id="publictext"> - Public</label>
                <input type="radio" id="public" name="privacy" value="public" required><br>
                      <?php include('errors.php'); ?>
                <br>
                <input id="submit" type="submit" class="btn btn-primary btn-sm" value="Create Channel" name="createchannel">
            </form>
            </div>
            </div>
                <?php include "includes/notifications.php" ?>
            </div>
            </div>
                <?php include "includes/footer.php" ?>
        </body>
    </html>
    