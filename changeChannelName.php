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
        <?php include "includes/carousel.html" ?>
    <div class="container">
        
        <br>

        <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-4 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Change Name</h2>
             </div></div>
            </div>
            <div class = "col-lg-4 col-md-4">
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
    <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
            
        <div class = "card border-0">
        <div class = "card body border-0">
            <ul class="list-group list-group-flush">
            
            
            <li class="list-group-item border-0">
                <form method="post">
                    <div class="input-group">
                        <label id="newchanneltext">Enter your new channel name:&nbsp;&nbsp;</label>
                        <input id="channelname" type="text" name="channelname1">
                    </div>
                
                    <div class="input-group">
                        <label id="renewchanneltext">Re-enter your new channel name:&nbsp;&nbsp;</label>
                        <input id="rechannelname" type="text" name="channelname2">
                    </div>
                    <br>
                    <div class="input-group">
                        <button id="confirm" type="submit" name="changechannelname" onclick= "confirm('Are you sure you want to change your channel name?')">Set New Channel Name</button>
                    </div>
                </form>
            </li>
        </div>
        </div>
        
<?php

        $errors = array(); 
        if (isset($_POST['changechannelname'])) {
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $channel_id = $_GET['channel'];
            $query12 = "SELECT * From Channel WHERE channelID='$channel_id'";
            $result = mysqli_query($conn, $query12);
            $channel = mysqli_fetch_assoc($result);
            $channelName = $channel['channelName'];
        
            $channelName1 = $_POST['channelname1'];
            $channelName2 = $_POST['channelname2'];
        
            $i = 0;
        
           
            
            
            if (empty($channelName1)) {
      	        array_push($errors ,"Please enter a channel name!");
            }
            if (empty($channelName2)) {
      	        array_push($errors ,"Repeat channel name is required!");
            }
        
            if (!($channelName1 == $channelName2)) {
               array_push($errors ,"Repeat channel name must match channel name!");
            }
        
            $channelname_check_query = "SELECT * FROM Channel WHERE channelName ='$channelName1' ";
            $result = mysqli_query($conn, $channelname_check_query);
            $cname = mysqli_fetch_assoc($result);
        
            if ($cname) { 
                if ($cname['channelName'] == $channelName1) {
                    array_push($errors ,"Channel name already exists, please enter a new channel name");
                }
            }
            include('errors.php');
            if (count($errors) == 0) {
          	    $sql = "UPDATE Channel SET channelName='$channelName1' WHERE channelName='$channelName'";
      
          	    mysqli_query($conn, $sql);
          	
          	if ($conn->query($sql) === TRUE) {
                echo "Channel name changed successfully";
            } else {
              echo "Error changing channel name: " . $conn->error;
            }
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
    <?php include "includes/footer.php" ?>
</body>
</html>