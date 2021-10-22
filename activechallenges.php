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
<!-- Acitve CHALLENGES PAGE -->

<!-- required html -->
<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body style="background: linear-gradient(#ffffff, #d9d9d9);">
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html" ?>
    <!-- each challenge will have its own card, just setting rest of pages to standard layout for now -->
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
        <br>
        <div class = "row row-cols-3">
            <div class = "col-md-4 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Active Challenges</h2>
             </div></div>
            </div>
            <div class = "col-md-4">
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
    
        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        
     $username = $_SESSION['username'];

        
        $challenge_data = "SELECT * FROM Challenge" ;
        $results = mysqli_query($db, $challenge_data);
        $challenge_ids = array();
        $active_challenges = array();
        $index = 0;
        $challenge_index = 0;
        $active_challenge_counter = -1;

        
        $currDate1 = date("Y-m-d"); //today's date
        $currDate1 = new DateTime($currDate1); // convert into seconds


        
        if (mysqli_num_rows($results) == 0){
            echo '<div class = "card border-2 border-dark shadow-lg">';
            echo '<div class = "card body border-0">';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item border-0">';
            echo "There are no challenges.<br></li></ul></div></div>";
            
        }
        
        // counts how many challenges there are in challenge index
        // also stores all the challenge ID's in an array.
        while ($row = mysqli_fetch_array($results)) {
            $temp_CID = $row['challengeID'];
            $challenge_ids[$challenge_index] = $temp_CID;
            $challenge_index++;
        }
        
        $num_challenges = $challenge_index;
            
        while ($index < $num_challenges) {
            
            
            $tmp_id = $challenge_ids[$index];

            $tmp_challenge_info_query = "SELECT * FROM Challenge WHERE challengeID = '$tmp_id'";
            
            $results2 = mysqli_query($db, $tmp_challenge_info_query);
            $tmp_challenge_info = mysqli_fetch_assoc($results2);
            $tmp_challenge_start = ($tmp_challenge_info['startDate']);
            $tmp_challenge_end = ($tmp_challenge_info['endDate']);
            $tmp_challenge_userID = $tmp_challenge_info['userID'];
            $tmp_challenge_username_query = "SELECT * FROM Users WHERE userID = '$tmp_challenge_userID'";
            $results3 = mysqli_query($db,  $tmp_challenge_username_query);
            $tmp_challenge_user_info = mysqli_fetch_assoc($results3);
            $tmp_challenge_username = $tmp_challenge_user_info['username'];

            $currDate1 = date("Y-m-d"); 
            $currDate1 = new DateTime($currDate1); // convert into seconds
            $startdate1 = new DateTime($tmp_challenge_start); // convert the start date specified by the user into seconds
            $enddate1 = new DateTime($tmp_challenge_end); // convert the start date specified by the user into seconds*/
    
            if ($currDate1 >= $startdate1 && $currDate1 < $enddate1 ){
                
                echo '<div class = "card border-2 border-dark shadow-lg">';
                echo '<div class = "card body border-0">';
                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item border-0">';
                
                $tmp_challenge_name = $tmp_challenge_info['challengeName'];
                $tmp_challenge_descript = $tmp_challenge_info['description'];
                

                echo "<h4>".$tmp_challenge_name."</h4>";
                echo "Created By: <a href='youraccount.php?view_username=$tmp_challenge_username' style=\"color:#20b2aa\">".$tmp_challenge_username."</a><br>";
                echo "Description: ".$tmp_challenge_descript."<br>";
                echo "Start Date: ".$tmp_challenge_start."<br>";
                echo "End Date: ".$tmp_challenge_end."<br>";
                array_push($active_challenges,$challenge_ids[$index]);
                $active_challenge_counter++;
                $query = "SELECT * FROM Users WHERE username = '$username' ";
                $results = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results);
                $id = $user['userID'];
                $query = "SELECT * From Participants WHERE challengeID='$tmp_id' and userID='$id'";
                $results = mysqli_query($db, $query);
                $participant = mysqli_fetch_assoc($results);
                if ($participant) {
                    echo "<h5 style=\"color:coral\">You are a participant in this challenge.</h5>";            
                } else {
                    
                    echo "<div style=\"float: left; width: 130px\">
                    <form method=\"post\">
                                <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Join Challenge\"  name=\"join_challenge$active_challenge_counter\">
                            </form></div>";
                }
                          
                          echo "<div style=\"float: left; width: 130px\">
                                <form method=\"post\" action='viewchallenge.php?challenge=$tmp_id'>
                                    <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"View challenge\" name=\"viewchallenge\">
                                </form></div>
                            ";
                            
                            
                            if ($tmp_challenge_username == $username) {
                                echo '<div style=\"float: left; width: 130px\">';
                                echo "
                                <form method=\"post\" action='editChallenge.php?challenge=$tmp_id'>
                                    <input type=\"submit\" class=\"btn btn-primary btn-sm\" value=\"Edit challenge\" name=\"viewchallenge\">
                                </form><br>
                            ";
                            echo '</div>';
                            }
                            
                    echo '</li></ul></div></div><br><br>';
                    }
                    
            $index++;
            
            
        }
        
        
        $arr_size = count($active_challenges);
        $index = 0;
        for($index; $index < $arr_size; $index++ ) {
            $tempname = "join_challenge$index";
            
            if (isset($_POST[$tempname]))  {
                $query = "SELECT * FROM Users WHERE username = '$username' ";
                $results3 = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($results3);
                $id = ($user['userID']);
                $tmp_id2 = $active_challenges[$index];
                      
                $query3 = "SELECT * FROM Participants WHERE userID = '$id' AND challengeID = '$tmp_id2' ";
                $result4 = mysqli_query($db, $query3);
                $row = mysqli_fetch_row($result4);

                    if (is_null($row)) {
                        // this works
                        $query2 = "INSERT INTO Participants (challengeID, userID) VALUES ('$tmp_id2','$id')";
                        $results6 = mysqli_query($db, $query2);
                        echo "You're all signed up <br>";
                        echo("<meta http-equiv='refresh' content='1'>");
                                        
                    } else  {
                        echo "You've already signed up for this challenge <br>";
                        echo("<meta http-equiv='refresh' content='1'>");
                    }
                        
                unset($_POST[$tempname]);
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
<br>
</html>