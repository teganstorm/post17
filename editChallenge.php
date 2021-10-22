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
    <link rel="stylesheet" href="styles/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <?php include "includes/header.html";
    ?>
    <?php include "includes/carousel.html"?>
    
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
    
    <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
     <div class="container" style = "background-color:transparent">
         <br>
         
         <div class = "row row-cols-3">
            <div class = "col-md-3 col-5 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Challenge Settings</h2>
             </div></div>
            </div>
            <div class = "col-md-5">
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
    
        $db = mysqli_connect('localhost', 'g65postn_root','E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        
        $errors = array(); 
        $username = $_SESSION['username'];
        
        $challenge_id = $_GET['challenge'];
        $query = "SELECT * From Challenge WHERE challengeID='$challenge_id'";
        $result = mysqli_query($db, $query);
        $challenge = mysqli_fetch_assoc($result);
        $name = $challenge['challengeName'];
        $creatorID = $challenge['userID'];
        $start_date = $challenge['startDate'];
        $end_date = $challenge ['endDate'];
        $description = $challenge ['description'];
        
         echo '<div class = "card border-dark border-2 shadow-lg ">';
        echo '<div class = "card body border-0">';
        echo '<ul class="list-group list-group-flush">';
        echo '<li class="list-group-item border-0">';
        
        
        echo "<h5>Challenge name: $name<br></h5>";
        
        echo"<form method=\"post\" action = 'changeChallengeName.php?challenge=$challenge_id' >
             <input type=\"submit\" value=\"Change Name\" name=\"changename\" class=\"btn btn-primary btn-sm\" 
             >
             </form><br> ";
             
        echo "<h5>Challenge description: $description<br></h5>";
        echo "<form method=\"post\"action='changeChallengeDesc.php?challenge=$challenge_id' >
             <input type=\"submit\" value=\"Change Description\" name=\"changedesc\" class=\"btn btn-primary btn-sm\">
             </form><br> ";
             
        $currDate1 = date("Y-m-d"); //today's date
        $currDate1 = new DateTime($currDate1); // convert into seconds
        
        $startdate1 = new DateTime($start_date); // convert the start date specified by the user into seconds
        $interval = $currDate1->diff($startdate1)->days;
        
        if ($currDate1 > $startdate1 ){ //If the challenge has already stared, can't change the start date.
        
            echo "<h5>Start date: $start_date <br></h5>";
            echo "<h5>End date: $end_date<br></h5>";
            
            echo 
            "<form method=\"post\" >
            <input type=\"submit\" 
             
            class=\"btn btn-danger btn-sm\"
            value=\"Delete challenge\" 
            name=\"delete\" 
            onclick=\"return confirm('Are you sure you want to delete this challenge?')\"></form>";
            
        } else {
            
            echo "<h5>Start date: $start_date (starts in $interval days) <br></h5>";
            echo "<form method=\"post\"action='changeChallengeStarteDate.php?challenge=$challenge_id' >
                  <input type=\"submit\" value=\"Change Start Date\" name=\"changestartdate\" class=\"btn btn-primary btn-sm\">
                  </form><br> ";
             
            echo "<h5>End date: $end_date<br></h5>";
            echo "<form method=\"post\"action='changeChallengeEndDate.php?challenge=$challenge_id' >
                  <input type=\"submit\" value=\"Change End Date\" name=\"changeenddate\" class=\"btn btn-primary btn-sm\">
                  </form><br> ";
                  
            echo "<h5>Delete challenge<br></h5>";
        echo 
            "<form method=\"post\" >
            <input type=\"submit\" 
            class=\"btn btn-danger btn-sm\" 
            value=\"Delete challenge\" 
            name=\"delete\" 
            onclick=\"return confirm('Are you sure you want to delete this challenge?')\"></form>";
             
        }     
        echo "</li></ul></div></div><br><br>";  
        
        $post_ids = array();
        $posts_count = 0;
        $delete_query = "SELECT * FROM Posts WHERE challengeID = $challenge_id ";
        $result2 = mysqli_query($db, $delete_query);
        $post = mysqli_fetch_assoc($result);
        $postID = $post['postID'];
        
        
        while ($row = mysqli_fetch_array($result2)) {
            $temp_postid = $row['postID'];
            $post_ids[$posts_count] = $temp_postid;    
            $posts_count++;
        }


    
        $delete = "delete";
        $null_ = "NULL";
        if(isset($_POST[$delete])) {
            $index = 0;
          
            for ($index; $index < $posts_count; $index++) {
            $remove_postid = $post_ids[$index];
        
            // remove post from channel table
            $query1 = "UPDATE Posts SET challengeID = $null_ WHERE postID ='$remove_postid' ";
            
            mysqli_query($db,$query1);
  
    
            }
        
            $query2 = "DELETE FROM Participants WHERE challengeID = '$challenge_id'";
            mysqli_query($db,$query2);
            $removeChallengeQuery = "DELETE FROM Challenge WHERE challengeID = '$challenge_id' ";
            
            

        if ($db->query($removeChallengeQuery) === TRUE) {
                    } else {
                        echo "<br><br><br><br>it didn't work" . $removeChallengeQuery . "<br>" . $db->error;
                    }
    
        
        echo '<script>location.href="https://post17.net/challenges.php"</script>';

        }
        
               
            
        $q = "SELECT * FROM Posts WHERE challengeID='$challenge_id' ORDER BY likes DESC";
        $results = mysqli_query($db, $q);
        $posts_count = 0;
        $post_ids = array();
        
        
        
        while ($row = mysqli_fetch_array($results)) {
            echo '<div class = "card border-2 border-dark shadow-lg">';
            echo '<div class = "card body border-0 mt-2">';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item border-0">';
            $temp_postid = $row['postID'];
            $post_ids[$posts_count] = $temp_postid;
            $post_src = $row['imgSrc'];
            $post_likes = $row['likes'];
            $post_id = $row['postID'];
            echo "<img src=\"$post_src\" style=\"width:100%; border:2px solid black;\" class=\"card-img-top\"><br>";
                        // check if this post has been reported and if it has display the reason for report
            $q2 = "SELECT * FROM Report WHERE postID='$post_id'";
            $r = mysqli_query($db, $q2);
            while ($report = mysqli_fetch_array($r)) {
                $reason = $report['comment'];
                echo "<a style=\"font-weight:bold; color:coral\">     This post has been reported, reason for reporting: \"$reason\"<br></a>";
            }
            echo "Total <img src=\"images/like.png\" style=\"width:3%\">s: $post_likes <br>";
            
                            echo "
                                <form method=\"post\" action='viewpost.php?post=$post_id'>
                                    <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View post\" name=\"viewpost\">
                                </form>
                            ";
                            echo 
                            "<form method=\"post\">
                            <input type=\"submit\" 
                             
                            value=\"Remove\" 
                            name=\"remove$posts_count\" 
                            onclick=\"return confirm('Are you sure you want to remove this post?' )\" class=\"btn btn-danger btn-sm\"></form>";
            
            $posts_count++;
            echo '</li></ul></div></div><br><br>';
            
        }
        
        $index = 0;

         for ($index; $index < $posts_count; $index++) {
            $temp = "remove$index";
            if(isset($_POST[$temp])) {

                $remove_postid = $post_ids[$index];
                // remove post from channel table
                $query1 = "UPDATE Posts SET challengeID = $null_ WHERE postID = '$remove_postid' ";
                mysqli_query($db,$query1);
    
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
    <br><br>
</body>
</html>
    
