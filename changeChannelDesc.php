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
<body style="background: transparent">
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html" ?>
    
    <div class="container">
        <br>
        <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-6 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Change Description</h2>
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
        
    <div class="row row-cols-2">
          
    <div class="col-md-8 col-12 d-block">
    <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
            
        <div class = "card border-0">
        <div class = "card body border-0">
            <ul class="list-group list-group-flush">
            
            
            <li class="list-group-item border-0">
                <form method="post">
                    <div class="input-group">
                       
                        <br><br>
                        <textarea id="channeldesc" name="channeldesc" rows="7" cols="30">Enter your new description here.</textarea>
                        
                        <br>
          
       
                    </div>
                
                    <br>
                    <div class="input-group">
                        <button id="confirm" type="submit" name="changechanneldesc" onclick= "confirm('Are you sure you want to change your channel description?')">Set New Channel Description</button>
                    </div>
                </form>
            </li>
        </div>
        </div>
        
<?php

        $errors = array(); 
        if (isset($_POST['changechanneldesc'])) {
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $channel_id = $_GET['channel'];
            $query12 = "SELECT * From Channel WHERE channelID='$channel_id'";
            $result = mysqli_query($conn, $query12);
            $channel = mysqli_fetch_assoc($result);
            $channelDesc = $channel['description'];
            $channelname = $channel['channelName'];
        
            $channelDesc1 = $_POST['channeldesc'];

            $i = 0;
        
            if (empty($channelDesc1)) {
      	        array_push($errors ,"Please enter a channel description!");
            }
        
            $channelname_check_query = "SELECT * FROM Channel WHERE channelName ='$channelname' ";
            $result = mysqli_query($conn, $channelname_check_query);
            $cname = mysqli_fetch_assoc($result);
        
            if ($cname) { 
                if ($cname['description'] == $channelDesc1) {
                    array_push($errors ,"Channel description already exists, please enter a new channel description");
                }
            }
            include('errors.php');
            if (count($errors) == 0) {
          	    $sql = "UPDATE Channel SET description='$channelDesc1' WHERE channelName='$channelname'";
          	    echo $sql;
      
          	    mysqli_query($conn, $sql);
          	
          	if ($conn->query($sql) === TRUE) {
                echo "Channel description changed successfully";
            } else {
              echo "Error changing channel description: " . $conn->error;
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