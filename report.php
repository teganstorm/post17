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
        <?php
              if (isset($_POST['reportbutton'])) {
                   $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                   
              // we need the reason
              $reason = $_POST['reason'];
              if(strlen($reason)<25){
              
              // we need userID
                $username = $_SESSION['username'];
                  $query_userid = "SELECT * FROM Users WHERE username='$username'";
                  $result_users = mysqli_query($db, $query_userid);
                  $user = mysqli_fetch_assoc($result_users);
                  $id = $user['userID'];
              
              // we need postID
              $post_id = $_GET['post'];
              
              // now to the query
                $query_addreason = "INSERT INTO Report (postID, userID, comment, seen) 
                VALUES('$post_id', '$id', '$reason', '0')";
                mysqli_query($db, $query_addreason);
                    $succesfullMessage = "Successfully reported post!";
                    $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
                
          } else {
              echo "Reason is too long, please keep it below 25 characters";
          }
              }
         
        ?>
<!-- Report PAGE -->
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
            <div class = "col-lg-3 col-md-4 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Report Post</h2>
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
        
        <!-- Main content of current page -->
        <div class="col-md-8 col-12 d-block">
        <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
    
        <form method="post">
            <?php echo $messageecho ?>
            <p>We have a strict policy which states no pictures which show faces are allowed on our site. <br>Posts which do not follow our policy should be reported so they can be removed from the site.</p>
            
            <p>We're sorry you feel the need to report this post.<br>Please let us know why.</p>
            
            <label id="reasontext">Reason for reporting this post:</label><br>
            <input type="text" id="reason" name="reason" required><br>

            <div class="text-center">
            <input class=" btn btn-warning " id="reportbutton" type="submit" name="reportbutton" value="Report Post"/>
            </div>
        </form>

    
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