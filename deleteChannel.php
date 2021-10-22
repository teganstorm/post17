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
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container">
      <div class="row">
        <div class="col-sm-8 col-12 d-block">
        <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
            
            
            <?php
            
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